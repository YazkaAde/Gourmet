<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reservation_id',
        'user_id',
        'amount',
        'amount_paid',
        'change',
        'payment_method',
        'status',
        'receipt_url',
        'notes',
        'bank_name',
        'account_number',
        'qriss_issuer',
        'qris_number',
        'card_type',
        'card_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    
    const METHOD_CASH = 'cash';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_E_WALLET = 'e_wallet';
    const METHOD_QRIS = 'qris';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeReservationPayments($query)
    {
        return $query->whereNotNull('reservation_id');
    }

    public function scopeOrderPayments($query)
    {
        return $query->whereNotNull('order_id');
    }

    public function scopeCash($query)
    {
        return $query->where('payment_method', self::METHOD_CASH);
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('payment_method', self::METHOD_BANK_TRANSFER);
    }

    public function scopeEWallet($query) 
    {
        return $query->where('payment_method', self::METHOD_E_WALLET);
    }

    public function scopeQris($query)
    {
        return $query->where('payment_method', self::METHOD_QRIS);
    }

    // Helper methods
    public function isForReservation(): bool
    {
        return !is_null($this->reservation_id);
    }

    public function isForOrder(): bool
    {
        return !is_null($this->order_id);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isCash(): bool
    {
        return $this->payment_method === self::METHOD_CASH;
    }

    public function isBankTransfer(): bool
    {
        return $this->payment_method === self::METHOD_BANK_TRANSFER;
    }

    public function isEWallet(): bool 
    {
        return $this->payment_method === self::METHOD_E_WALLET;
    }

    public function isQris(): bool
    {
        return $this->payment_method === self::METHOD_QRIS;
    }

    public function needsCashierAction(): bool
    {
        return $this->isPending() && (
            $this->isCash() || 
            $this->isBankTransfer() || 
            $this->isEWallet() || 
            $this->isQris()
        );
    }

    public function getFormattedAmount(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedAmountPaid(): string
    {
        return $this->amount_paid ? 'Rp ' . number_format($this->amount_paid, 0, ',', '.') : '-';
    }

    public function getFormattedChange(): string
    {
        return $this->change ? 'Rp ' . number_format($this->change, 0, ',', '.') : '-';
    }

    public function getPaymentDetails(): array
    {
        $details = [];
        
        if ($this->isBankTransfer()) {
            $details = [
                'Bank' => $this->bank_name,
                'Account Number' => $this->account_number,
            ];
        } elseif ($this->isEWallet()) {
            $details = [
                'E-Wallet' => $this->bank_name,
                'Account Number' => $this->account_number,
            ];
        } elseif ($this->isQris()) {
            $details = [
                'QRIS Issuer' => $this->qriss_issuer,
                'QRIS Number' => $this->qris_number,
            ];
        } elseif ($this->isCash()) {
            $details = [
                'Amount Paid' => $this->getFormattedAmountPaid(),
                'Change' => $this->getFormattedChange(),
            ];
        }
        
        return $details;
    }

    private function maskCardNumber(?string $cardNumber): string
    {
        if (!$cardNumber) return '-';
        
        return substr($cardNumber, 0, 4) . '****' . substr($cardNumber, -4);
    }

    public static function getValidationRules(string $paymentMethod): array
    {
        $baseRules = [
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet,qris', 
            'notes' => 'nullable|string|max:500',
        ];

        $methodRules = [
            'cash' => [
                'amount_paid' => 'required|numeric|min:' . request('amount'),
                'amount' => 'required|numeric|min:0.01',
            ],
            'bank_transfer' => [
                'bank_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:50',
            ],
            'e_wallet' => [
                'bank_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:50',
            ],
            'qris' => [
                'qriss_issuer' => 'required|string|max:50',
                'qris_number' => 'required|string|max:100',
            ],
        ];

        return array_merge($baseRules, $methodRules[$paymentMethod] ?? []);
    }
}