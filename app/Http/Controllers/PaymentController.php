<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Midtrans;

class PaymentController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        Midtrans\Config::$serverKey = 'SB-Mid-server-6rtC6D8yaJAub4YnQIW-POt5';
        Midtrans\Config::$isProduction = false;
        Midtrans\Config::$isSanitized = true;
        Midtrans\Config::$is3ds = false;
        Midtrans\Config::$clientKey = 'SB-Mid-client-259kqKpKF_PGbWcU';
    }

    public function getPayment()
    {
        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 100000,
            ),
        );

        try {
            // Get snap payment page URL
            $paymentUrl = Midtrans\Snap::createTransaction($params)->redirect_url;

            // Redirect to payment URL
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
