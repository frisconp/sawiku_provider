<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans;

class OrderController extends Controller
{
    public function __construct(Request $request)
    {
        // Midtrans Configurations
        Midtrans\Config::$serverKey = 'SB-Mid-server-6rtC6D8yaJAub4YnQIW-POt5';
        Midtrans\Config::$isProduction = false;
        Midtrans\Config::$isSanitized = true;
        Midtrans\Config::$is3ds = false;
        Midtrans\Config::$clientKey = 'SB-Mid-client-259kqKpKF_PGbWcU';
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        // Save order data
        $order = Order::create([
            'user_id' => $request->user()->id,
            'payment_total' => $request->payment_total,
            'note' => $request->note,
        ]);

        if (empty($request->order_items) || count($request->order_items) == 0) {
            DB::rollback();
            return response()->json(['error_message' => 'Array is empty.'], 400);
        }

        // Save all order detail data
        foreach ($request->order_items as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'amount' => $item['amount'],
            ]);
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->payment_total,
            ],
        ];

        $snapTransaction = Midtrans\Snap::createTransaction($payload);
        $snapToken = $snapTransaction->token;
        $paymentUrl = $snapTransaction->redirect_url;

        $order->payment_token = $snapToken;
        $order->save();

        DB::commit();
        return response()->json($snapTransaction);
    }

    public function notificationHandler()
    {
        $notification = new Midtrans\Notification();

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;
        $order = Order::findOrFail($orderId);

        error_log("Order ID " . $notification->order_id . ": transaction status = " . $transaction . ", fraud status = " . $fraud);

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->setPending();
                } else {
                    $order->setSuccess();
                }
            }
        } else if ($transaction == 'settlement') {
            $order->setSuccess();
        } else if ($transaction == 'pending') {
            $order->setPending();
        } else if ($transaction == 'deny') {
            $order->setFailed();
        } else if ($transaction == 'expire') {
            $order->setExpired();
        } else if ($transaction == 'cancel') {
            $order->setFailed();
        }

        return;
    }

    public function getByUser(Request $request)
    {
        $userId = $request->user()->id;
        $orders = Order::where('user_id', $userId)->with('details')->get();

        return $this->sendResponse($orders, 'Get orders data success.');
    }
}
