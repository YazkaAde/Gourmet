<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'menu_id',
        'order_id',
        'reservation_id',
        'rating',
        'comment',
        'admin_reply',
        'replied_at'
    ];

    protected $casts = [
        'rating' => 'integer',
        'replied_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function scopePendingReply($query)
    {
        return $query->whereNull('admin_reply');
    }

    public function scopeReplied($query)
    {
        return $query->whereNotNull('admin_reply');
    }

    public function isReplied(): bool
    {
        return !is_null($this->admin_reply);
    }

    public static function hasUserReviewedMenu($userId, $menuId)
    {
        return self::where('user_id', $userId)
                    ->where('menu_id', $menuId)
                    ->exists();
    }

    public static function getUserReviewForMenu($userId, $menuId)
    {
        return self::where('user_id', $userId)
                    ->where('menu_id', $menuId)
                    ->first();
    }

    public static function getUserReviewForMenuInContext($userId, $menuId, $orderId = null, $reservationId = null)
    {
        $query = self::where('user_id', $userId)
                    ->where('menu_id', $menuId);

        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        if ($reservationId) {
            $query->where('reservation_id', $reservationId);
        }

        return $query->first();
    }
}