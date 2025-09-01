<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'menu_id',
        'order_id',
        'rating',
        'comment',
        'admin_reply',
        'replied_at'
    ];

    protected $casts = [
        'rating' => 'integer',
        'replied_at' => 'datetime'
    ];

    /**
     * Get the user that owns the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the menu that owns the review.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the order that owns the review.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include reviews that have not been replied to.
     */
    public function scopePendingReply($query)
    {
        return $query->whereNull('admin_reply');
    }

    /**
     * Scope a query to only include replied reviews.
     */
    public function scopeReplied($query)
    {
        return $query->whereNotNull('admin_reply');
    }

    /**
     * Check if the review has been replied to by admin.
     */
    public function isReplied(): bool
    {
        return !is_null($this->admin_reply);
    }
}