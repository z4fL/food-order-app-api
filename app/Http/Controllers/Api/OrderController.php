<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Events\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders with their details.
     *
     * Retrieves all orders from the database along with their associated details,
     * and returns them as a resource collection.
     *
     * @return \App\Http\Resources\OrderResource
     */
    public function index()
    {
        $orders = Order::with('details')
            ->where('status', 'diproses')
            ->orderByDesc('created_at')
            ->get();

        // Get all produk_id from all order details
        $produkIds = $orders->flatMap(function ($order) {
            return $order->details->pluck('produk_id');
        })->unique();

        // Fetch produk_id => kategori mapping
        $produkKategori = Produk::whereIn('id', $produkIds)
            ->pluck('kategori', 'id');

        // Attach kategori to each detail
        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $detail->kategori = $produkKategori[$detail->produk_id] ?? null;
            }
        }

        return new OrderResource(true, "List data Orders", $orders);
    }

    public function indexCashier()
    {
        $orders = Order::with('details')
            ->where('status', 'belum dibayar')
            ->orderByDesc('created_at')
            ->get();

        // Get all produk_id from all order details
        $produkIds = $orders->flatMap(function ($order) {
            return $order->details->pluck('produk_id');
        })->unique();

        // Fetch produk_id => kategori mapping
        $produkKategori = Produk::whereIn('id', $produkIds)
            ->pluck('kategori', 'id');

        // Attach kategori to each detail
        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $detail->kategori = $produkKategori[$detail->produk_id] ?? null;
            }
        }

        return new OrderResource(true, "List data Orders (Cashier)", $orders);
    }

    /**
     * Store a newly created order in storage.
     *
     * Validates the incoming request data for creating an order, including order details.
     * Calculates the total price, creates the order and its associated details within a database transaction.
     * Returns a JSON response with the created order data on success, or an error message on failure.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request containing order data.
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\OrderResource
     *
     * @throws \Illuminate\Validation\ValidationException If the request validation fails.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'catatan' => 'nullable|string',
            'meja' => 'required|integer|min:1|max:5',
            'details' => 'required|array|min:1',
            'details.*.produk_id' => 'required|integer',
            'details.*.nama_produk' => 'required|string',
            'details.*.harga_produk' => 'required|integer',
            'details.*.qty' => 'required|integer|min:1',
        ]);

        if (!$validated) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
            ], 422);
        }

        // Check if meja is present in request and status is not 'dibayar' in existing orders
        if ($request->filled('meja')) {
            $mejaInUse = Order::where('meja', $request->meja)
                ->where('status', '!=', 'diantar')
                ->exists();

            if ($mejaInUse) {
                return response()->json([
                    'status' => false,
                    'message' => 'Meja sedang digunakan!.',
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            foreach ($request->details as $item) {
                $totalHarga += $item['harga_produk'] * $item['qty'];
            }

            $order = Order::create([
                'uuid' => Str::uuid(),
                'meja' => $request->meja,
                'catatan' => $request->catatan,
                'total_harga' => $totalHarga,
                'status' => 'belum dibayar',
            ]);

            foreach ($request->details as $item) {
                $order->details()->create([
                    'produk_id' => $item['produk_id'],
                    'nama_produk' => $item['nama_produk'],
                    'harga_produk' => $item['harga_produk'],
                    'qty' => $item['qty'],
                    'total_harga' => $item['harga_produk'] * $item['qty'],
                ]);
            }

            DB::commit();

            $data = $order->load('details');

            // Get all produk_id from details
            $produkIds = $data->details->pluck('produk_id')->unique();

            // Fetch produk_id => kategori mapping
            $produkKategori = Produk::whereIn('id', $produkIds)
                ->pluck('kategori', 'id');

            // Attach kategori to each detail
            foreach ($data->details as $detail) {
                $detail->kategori = $produkKategori[$detail->produk_id] ?? null;
            }

            event(new OrderCreated($data));

            return new OrderResource(true, 'Order berhasil dibuat', ['uuid' => $order->uuid]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data Order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified order.
     *
     * Retrieves and returns the details of a specific order, including its related details.
     * If the order is not found, returns a JSON response with a 404 status code.
     *
     * @param  \App\Models\Order  $order  The order instance to display.
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\OrderResource
     */
    public function show(Order $order)
    {
        // Load order details
        $order->load('details');

        // Get all produk_id from details
        $produkIds = $order->details->pluck('produk_id')->unique();

        // Fetch produk data with id, gambar, and kategori
        $produkData = Produk::whereIn('id', $produkIds)->get(['id', 'gambar', 'kategori'])->keyBy('id');

        // Attach gambar and kategori to each detail
        $order->details->transform(function ($detail) use ($produkData) {
            $produk = $produkData[$detail->produk_id] ?? null;
            $detail->gambar = $produk->gambar ?? null;
            $detail->kategori = $produk->kategori ?? null;
            return $detail;
        });

        return new OrderResource(true, 'Berhasil mendapatkan data Order', $order);
    }

    /**
     * Update the specified order's status.
     *
     * Validates the incoming request to ensure the 'status' field, if present, is one of:
     * 'pending', 'diproses', or 'dibayar'. If the 'status' field is provided and valid,
     * updates the order's status accordingly and saves the changes.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing update data.
     * @param  \App\Models\Order         $order    The order instance to be updated.
     * @return \App\Http\Resources\OrderResource   The resource response containing the updated order and its details.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'in:diproses,diantar',
        ]);

        if ($request->filled('status')) {
            $order->status = $request->status;
        }

        $order->save();

        event(new OrderUpdated($order->load('details')));

        return new OrderResource(true, 'Berhasil merubah data Order', $order->load('details'));
    }

    public function updateCashier(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'in:diproses,diantar',
        ]);

        if ($request->filled('status')) {
            $order->status = $request->status;
        }

        $order->save();

        event(new OrderPaid($order->load('details')));

        return new OrderResource(true, 'Berhasil merubah data Order', $order->load('details'));
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Models\Order  $order  The order instance to be deleted.
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\OrderResource
     *
     * Returns a JSON response with status and message if the order is not found,
     * otherwise deletes the order and returns a success resource response.
     */
    public function destroy(Order $order)
    {
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order tidak ditemukan',
                'data' => null
            ], 404);
        }

        $order->delete();

        return new OrderResource(true, 'Berhasil menghapus data Order', null);
    }

    public function pay(Order $order)
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $apiInstance = new InvoiceApi();

        if ($order->checkout_link) {
            return response()->json([
                'status' => true,
                'message' => 'Invoice sudah dibuat',
                'checkout_link' => $order->checkout_link,
            ]);
        }

        $externalId = 'INV-' . uniqid();

        $create_invoice_request = new CreateInvoiceRequest([
            'external_id' => $externalId,
            'description' => 'Pembayaran untuk Meja ' . $order->meja,
            'amount' =>  $order->total_harga,
            'currency' => 'IDR',
            'invoice_duration' => 1800,
            'success_redirect_url' => "https://food-order-duade.netlify.app/detail-order/" . $order->uuid . "?status=pembayaran_sukses",
            'failure_redirect_url' => "https://food-order-duade.netlify.app/detail-order/" . $order->uuid . "?status=pembayaran_gagal"
        ]);

        DB::beginTransaction();

        try {
            $invoice = $apiInstance->createInvoice($create_invoice_request);

            $order->update([
                'external_id' => $externalId,
                'checkout_link' => $invoice['invoice_url'],
                'status_xendit' => $invoice['status'],
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Invoice berhasil dibuat',
                'checkout_link' => $invoice['invoice_url'],
            ]);
        } catch (\Xendit\XenditSdkException $e) {
            DB::rollBack();
            Log::error('Xendit Error: ' . $e->getMessage());
            Log::error('Full Error: ' . json_encode($e->getFullError()));

            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat invoice',
                'error' => $e->getMessage(),
                'details' => $e->getFullError(),
            ], 500);
        }
    }
}
