<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;

class PaymentStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $payment;
    public $oldStatus;
    public $newStatus;

    public function __construct(Payment $payment, $oldStatus, $newStatus)
    {
        $this->payment = $payment;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}