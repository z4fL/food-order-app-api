<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderUpdated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        if ($request->header('X-CALLBACK-TOKEN') !== config('services.xendit.webhook_secret')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status'); // "PAID", "EXPIRED", etc.

        $order = Order::where('external_id', $externalId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status_xendit = $status;

        if ($status === 'PAID') {
            $order->status = 'diproses';
        }

        $order->save();

        event(new OrderUpdated($order->load('details')));

        return response()->json(['message' => 'Webhook processed']);
    }
}
