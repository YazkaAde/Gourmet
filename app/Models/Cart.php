<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'menu_id', 'quantity', 'order_id', 'price'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getPriceAttribute()
    {
        return $this->menu->price * $this->quantity;
    }

    // Scope untuk cart items yang belum memiliki order
    public function scopeWithoutOrder($query)
    {
        return $query->whereNull('order_id');
    }
}