<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder_name',
        'type',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeBankTransfer($query)
    {
        return $query->where('type', 'bank_transfer');
    }

    public function scopeEWallet($query) 
    {
        return $query->where('type', 'e_wallet');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isBankTransfer(): bool
    {
        return $this->type === 'bank_transfer';
    }

    public function isEWallet(): bool 
    {
        return $this->type === 'e_wallet';
    }

    public function getFormattedAccount(): string
    {
        return "{$this->bank_name} - {$this->account_number} ({$this->account_holder_name})";
    }
}