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
        'end_time',
        'guest_count',
        'table_number',
        'status',
        'notes'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'reservation_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
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
    
    if ($this->hasPreOrder()) {
        $allOrdersCompleted = true;
        foreach ($this->orders as $order) {
            if ($this->hasProcessingOrCompletedOrder() && $order->status !== 'completed') {
                $allOrdersCompleted = false;
                break;
            }
        }
        
        try {
            $dateString = $this->reservation_date . ' ' . $this->end_time;
            $reservationEndDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ':00');
            $isTimePassed = now()->greaterThanOrEqualTo($reservationEndDateTime);
            
            return $isFullyPaid && $allOrdersCompleted && $isTimePassed;
        } catch (\Exception $e) {
            \Log::error('DateTime parsing error in reservation', [
                'id' => $this->id,
                'date' => $this->reservation_date,
                'end_time' => $this->end_time,
                'error' => $e->getMessage()
            ]);
            
            return $isFullyPaid && $allOrdersCompleted;
        }
    } else {
        try {
            $dateString = $this->reservation_date . ' ' . $this->end_time;
            $reservationEndDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ':00');
            $isTimePassed = now()->greaterThanOrEqualTo($reservationEndDateTime);
            
            return $isFullyPaid && $isTimePassed;
        } catch (\Exception $e) {
            \Log::error('DateTime parsing error in reservation', [
                'id' => $this->id,
                'date' => $this->reservation_date,
                'end_time' => $this->end_time,
                'error' => $e->getMessage()
            ]);
            
            return $isFullyPaid;
        }
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

    public function calculateEndTime($reservationTime)
    {
        $time = \Carbon\Carbon::createFromFormat('H:i', $reservationTime);
        return $time->copy()->addHours(1)->format('H:i');
    }

    public function isWithinBusinessHours($time)
    {
        $time = \Carbon\Carbon::createFromFormat('H:i', $time);
        $start = \Carbon\Carbon::createFromTime(9, 0);
        $end = \Carbon\Carbon::createFromTime(21, 0);
        
        return $time->between($start, $end);
    }

    public function isEndTimeValid($startTime, $endTime)
    {
        $start = \Carbon\Carbon::createFromFormat('H:i', $startTime);
        $end = \Carbon\Carbon::createFromFormat('H:i', $endTime);
        
        $diffInHours = $end->diffInHours($start);
        
        return $diffInHours >= 1 && $this->isWithinBusinessHours($endTime);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && 
               $this->payments()->where('status', 'paid')->exists();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getReviewableMenus()
    {
        return $this->orderItems->map(function ($orderItem) {
            return $orderItem->menu;
        })->unique('id');
    }

    public function getFormattedReservationTimeAttribute()
    {
        return $this->reservation_time->format('H:i');
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time->format('H:i');
    }

    public function getFormattedReservationDateAttribute()
    {
        return $this->reservation_date->format('l, d-m-Y');
    }

    // Logic untuk pre order
    public function isMenuEditable()
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
            !$this->hasProcessingOrCompletedOrder();
    }

    public function hasProcessingOrCompletedOrder()
    {
        return $this->orders()
            ->whereIn('status', ['completed'])
            ->exists();
    }

    public function canEditMenu()
    {
        return !in_array($this->status, ['completed', 'cancelled']) && 
            !$this->hasProcessingOrCompletedOrder();
    }

public function canReduceMenu()
{
    return in_array($this->status, ['pending', 'confirmed']) && 
           !$this->hasProcessingOrCompletedOrder();
}

public function canAddMenu()
{
    return in_array($this->status, ['pending', 'confirmed']) && 
           !$this->hasProcessingOrCompletedOrder();
}

public function hasProcessingOrder()
{
    return $this->orders()
        ->where('status', 'processing')
        ->exists();
}

public function hasPendingOrder()
{
    return $this->orders()
        ->where('status', 'pending')
        ->exists();
}

public function getAllOrderItems()
{
    return $this->orderItems()->with('menu')->get();
}

public function getGroupedOrderItems()
{
    return $this->orderItems()
        ->with('menu')
        ->get()
        ->groupBy('menu_id');
}

public function refreshMenuTotal()
{
    $menuTotal = $this->orderItems()->sum('total_price');
    $this->update([
        'menu_total' => $menuTotal,
        'total_amount' => $this->reservation_fee + $menuTotal
    ]);
    return $this;
}
}