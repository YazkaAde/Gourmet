<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_id',
        'table_number',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(NumberTable::class, 'table_number', 'table_number');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'processing']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeHasPayment($query)
    {
        return $query->whereHas('payments');
    }

    public function scopeNeedsPayment($query)
    {
        return $query->where('status', 'completed')
                    ->whereDoesntHave('payments')
                    ->orWhereHas('payments', function($q) {
                        $q->where('status', 'pending');
                    });
    }

    public function isFromReservation()
    {
        return !is_null($this->reservation_id);
    }
}