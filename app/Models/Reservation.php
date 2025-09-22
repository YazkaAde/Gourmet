<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Events\ReservationStatusUpdated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'table_number',
        'status',
        'notes'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
    ];

    protected $appends = [
        'reservation_fee',
        'total_amount',
        'remaining_payment',
        'down_payment_amount',
        'table_capacity',
        'total_paid',
        'cancellation_fee',
        'menu_total',
        'is_fully_paid'
    ];

    protected $dispatchesEvents = [
        'updated' => ReservationStatusUpdated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preOrderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'reservation_id')->whereNull('order_id');
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(NumberTable::class, 'table_number', 'table_number');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getOrderItemsWithFallback(): Collection
    {
        return $this->orderItems ?? new Collection();
    }

    public function hasOrder()
    {
        return $this->orders()->exists();
    }

    public function getReservationFeeAttribute()
    {
        $table = $this->getTableObjectAttribute();
        
        if (!$table) {
            return 0;
        }
        
        $tableCapacity = $table->table_capacity;
        $basePrice = $tableCapacity * 10000;
        
        if ($tableCapacity >= 8) {
            $basePrice = $basePrice * 0.8;
        }
        
        return $basePrice;
    }

    public function getMenuTotalAttribute()
    {
        return $this->orderItems()->sum('total_price');
    }

    public function hasPreOrder()
    {
        return $this->orderItems()->exists();
    }

    public function getCancellationFeeAttribute()
    {
        if (!$this->hasPreOrder()) {
            return 0;
        }
        
        $totalFoodPrice = $this->menu_total;
        return $totalFoodPrice * 0.5;
    }

    public function hasPaidPayment()
    {
        return $this->payments()->where('status', 'paid')->exists();
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'paid')->sum('amount');
    }

    public function getTotalAmountAttribute()
    {
        $reservationFee = $this->reservation_fee;
        $menuTotal = $this->menu_total;
        
        return $reservationFee + $menuTotal;
    }

    public function getRemainingPaymentAttribute()
    {
        $totalPaid = $this->total_paid;
        return max($this->total_amount - $totalPaid, 0);
    }

    public function getDownPaymentAmountAttribute()
    {
        return $this->total_amount * 0.1;
    }

    public function hasSufficientDownPayment()
    {
        $totalPaid = $this->total_paid;
        return $totalPaid >= $this->down_payment_amount;
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->remaining_payment <= 0;
    }

    public function getTableCapacityAttribute()
    {
        $table = $this->getTableObjectAttribute();
        
        if (!$table) {
            return 0;
        }
        
        return $table->table_capacity;
    }

    public function getTableObjectAttribute()
    {
        if (is_string($this->table_number)) {
            return NumberTable::where('table_number', $this->table_number)->first();
        }
        
        if (is_object($this->table)) {
            return $this->table;
        }
        
        return NumberTable::where('table_number', $this->table_number)->first();
    }

    public function shouldBeCompleted()
    {
        $isFullyPaid = $this->is_fully_paid;
        
        $allOrdersCompleted = true;
        foreach ($this->orders as $order) {
            if ($order->status !== 'completed') {
                $allOrdersCompleted = false;
                break;
            }
        }
        
        try {
            $dateString = $this->reservation_date . ' ' . $this->reservation_time;
            
            if (!strtotime($dateString)) {
                throw new \Exception('Invalid datetime format');
            }
            
            $reservationDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ':00');
            $oneHourAfterReservation = $reservationDateTime->addHour();
            $isTimePassed = now()->greaterThanOrEqualTo($oneHourAfterReservation);
            
            return ($isFullyPaid && $allOrdersCompleted) || $isTimePassed;
        } catch (\Exception $e) {
            \Log::error('DateTime parsing error in reservation', [
                'id' => $this->id,
                'date' => $this->reservation_date,
                'time' => $this->reservation_time,
                'error' => $e->getMessage()
            ]);
            
            return $isFullyPaid && $allOrdersCompleted;
        }
    }
    public function checkAndUpdateStatus()
    {
        if ($this->shouldBeCompleted() && $this->status !== 'completed') {
            $this->update(['status' => 'completed']);
            
            if ($this->orders()->exists()) {
                $this->orders()->update(['status' => 'completed']);
            }
            
            return true;
        }
        
        return false;
    }

    public function syncOrderItems()
{
    if ($this->orders()->exists()) {
        $order = $this->orders()->first();
        
        OrderItem::where('reservation_id', $this->id)
                ->whereNull('order_id')
                ->update(['order_id' => $order->id]);
                
        $menuTotal = $this->orderItems()->sum('total_price');
        $order->update(['total_price' => $menuTotal]);
    }
}
}