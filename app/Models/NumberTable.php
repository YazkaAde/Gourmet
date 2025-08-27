<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NumberTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'table_capacity'
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'table_number', 'table_number');
    }

    public function orders(): HasMany
{
    if (Schema::hasColumn('orders', 'table_number')) {
        return $this->hasMany(Order::class, 'table_number', 'table_number');
    }
    return $this->hasMany(Order::class)->whereRaw('1=0');
}

}