<?php

namespace App\Services;

use App\Models\Order;

class VnPayService
{
    public static function createPaymentUrl(Order $order, int $paymentId, string $clientIp): string
    {
        $config = config('vnpay');

        $vnpUrl     = $config['vnp_url'];
        $returnUrl  = $config['vnp_return_url'];
        $tmnCode    = $config['vnp_tmn_code'];
        $hashSecret = $config['vnp_hash_secret'];

        $amount = (int) ($order->total * 100);

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $tmnCode,
            'vnp_Amount'     => $amount,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $clientIp,
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => 'Thanh toan don hang ' . $order->code,
            'vnp_OrderType'  => 'other',
            'vnp_ReturnUrl'  => $returnUrl,
            'vnp_TxnRef'     => $paymentId,
        ];

        ksort($inputData);

        $hashDataArr = [];
        $queryArr    = [];

        foreach ($inputData as $key => $value) {
            $k = urlencode($key);
            $v = urlencode($value);

            $hashDataArr[] = $k . '=' . $v;
            $queryArr[]    = $k . '=' . $v;
        }

        $hashData = implode('&', $hashDataArr);
        $query    = implode('&', $queryArr);

        $secureHash = hash_hmac('sha512', $hashData, $hashSecret);

        return $vnpUrl . '?' . $query . '&vnp_SecureHash=' . $secureHash;
    }
}
