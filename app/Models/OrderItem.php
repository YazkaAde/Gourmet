<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reservation_id',
        'menu_id',
        'quantity',
        'price',
        'total_price'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->total_price ?? ($this->price * $this->quantity);
    }

    public function scopeFromReservation($query, $reservationId)
    {
        return $query->where('reservation_id', $reservationId);
    }

    public function scopeFromCart($query)
    {
        return $query->whereNull('reservation_id');
    }

    public function isFromReservation()
    {
        return !is_null($this->reservation_id);
    }
}
