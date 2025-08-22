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

    // Relasi ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke reservation
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    // Relasi ke number table
    public function table(): BelongsTo
    {
        return $this->belongsTo(NumberTable::class, 'table_number', 'table_number');
    }

    // Relasi ke carts (item-item dalam order)
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}