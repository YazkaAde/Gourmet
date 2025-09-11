<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'table_number',
        'status'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
    ];

    // Relasi ke user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke number table
    public function table(): BelongsTo
    {
        return $this->belongsTo(NumberTable::class, 'table_number', 'table_number');
    }

    // Relasi ke orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getReservationFeeAttribute()
    {
        $tableCapacity = 0;
        
        if (is_object($this->table) && property_exists($this->table, 'table_capacity')) {
            $tableCapacity = $this->table->table_capacity;
        } else {
            $table = NumberTable::where('table_number', $this->table_number)->first();
            if ($table) {
                $tableCapacity = $table->table_capacity;
            }
        }
        
        $basePrice = $tableCapacity * 10000;
        
        if ($tableCapacity >= 8) {
            $basePrice = $basePrice * 0.8;
        }
        
        return $basePrice;
    }

    public function hasPreOrder()
    {
        return $this->orders()->exists();
    }

// Hitung cancellation fee
    public function getCancellationFeeAttribute()
    {
        if (!$this->hasPreOrder()) {
            return 0;
        }
        
        $totalFoodPrice = $this->orders->sum('total_price');
        return $totalFoodPrice * 0.5;
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    
    public function hasPaidPayment()
    {
        return $this->payments()->where('status', '=', 'paid')->exists();
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->reservation_fee - $this->total_paid;
    }

    public function preOrderItems()
    {
        return $this->hasMany(PreOrderItem::class);
    }

    public function getTotalAmountAttribute()
    {
        $tableCapacity = 0;
        
        if (is_object($this->table) && property_exists($this->table, 'table_capacity')) {
            $tableCapacity = $this->table->table_capacity;
        } else {
            $table = NumberTable::where('table_number', $this->table_number)->first();
            if ($table) {
                $tableCapacity = $table->table_capacity;
            }
        }
        
        $reservationFee = $tableCapacity * 10000;
        
        if ($tableCapacity >= 8) {
            $reservationFee = $reservationFee * 0.8;
        }
        
        $menuTotal = 0;
        
        if ($this->relationLoaded('preOrderItems')) {
            $menuTotal = $this->preOrderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        } else {
            $menuTotal = PreOrderItem::where('reservation_id', $this->id)
                ->get()
                ->sum(function($item) {
                    return $item->price * $item->quantity;
                });
        }
        
        return $reservationFee + $menuTotal;
    }}
    