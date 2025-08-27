<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\NumberTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function availableTables()
    {
        $tables = NumberTable::whereDoesntHave('orders', function($query) {
            $query->whereIn('status', ['pending', 'processing']);
        })->get();
        
        return response()->json($tables);
    }
}