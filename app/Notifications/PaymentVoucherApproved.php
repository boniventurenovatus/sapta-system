<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentVoucherApproved extends Notification
{
    use Queueable;

    protected $voucher;

    public function __construct($voucher)
    {
        $this->voucher = $voucher;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Voucher Approved',
            'message' => 'Voucher ' . $this->voucher->voucher_number . ' has been approved.',
            'url' => '/payment-vouchers/' . $this->voucher->id,
            'icon' => 'check-circle',
            'type' => 'success',
        ];
    }
}
