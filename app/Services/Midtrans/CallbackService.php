<?php

namespace App\Services\Midtrans;

use App\Models\Order;
use App\Services\Midtrans\Midtrans;
use Midtrans\Notification;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $order;
    protected $serverKey;

    public function __construct()
    {
        parent::__construct();

        $this->serverKey = config('midtrans.server_key');
        $this->_handleNotification();
    }

    public function isSignatureKeyVerified()
    {
        // dd($this->_createLocalSignatureKey(), $this->notification->signature_key);
        return ($this->_createLocalSignatureKey() == $this->notification->signature_key);
    }

    public function isSuccess()
    {
        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = !empty($this->notification->fraud_status) ? ($this->notification->fraud_status == 'accept') : true;

        return ($statusCode == 200 && $fraudStatus && ($transactionStatus == 'capture' || $transactionStatus == 'settlement'));
    }

    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }

    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getOrder()
    {
        return $this->order;
    }

    protected function _createLocalSignatureKey()
    {
        // $orderId = $this->order->code;
        // $statusCode = $this->notification->status_code;
        // // $grossAmount = $this->order->gross_amount;
        // $grossAmount = $this->notification->gross_amount;
        // $serverKey = $this->serverKey;
        // $input = $orderId . $statusCode . $grossAmount . $serverKey;
        // $signature = openssl_digest($input, 'sha512');
        // return $signature;
        return hash(
            'sha512',
            $this->notification->order_id . $this->notification->status_code .
                $this->notification->gross_amount . $this->serverKey
        );
    }

    protected function _handleNotification()
    {
        $notification = new Notification();

        $orderNumber = $notification->order_id;
        $order = Order::where('code', $orderNumber)->first();


        $this->notification = $notification;
        $this->order = $order;
    }
}
