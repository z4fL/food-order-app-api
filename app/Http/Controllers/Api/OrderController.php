<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            ->where('status', '!=', 'dibayar')
            ->orderByDesc('created_at')
            ->get();

        return new OrderResource(true, "List data Orders", $orders);
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
                ->where('status', '!=', 'dibayar')
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
                'status' => 'pending',
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

        // Fetch produk data with id and gambar
        $produkData = \App\Models\Produk::whereIn('id', $produkIds)->pluck('gambar', 'id');

        // Attach gambar to each detail
        $order->details->transform(function ($detail) use ($produkData) {
            $detail->gambar = $produkData[$detail->produk_id] ?? null;
            return $detail;
        });

        return new OrderResource(true, 'Berhasil mendapatkan data Order', $order);
    }

    /**
     * Update the specified order's status.
     *
     * Validates the incoming request to ensure the 'status' field, if present, is one of:
     * 'pending', 'diproses', or 'selesai'. If the 'status' field is provided and valid,
     * updates the order's status accordingly and saves the changes.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing update data.
     * @param  \App\Models\Order         $order    The order instance to be updated.
     * @return \App\Http\Resources\OrderResource   The resource response containing the updated order and its details.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'in:diproses,selesai,dibayar',
        ]);

        if ($request->filled('status')) {
            $order->status = $request->status;
        }

        $order->save();

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
}
