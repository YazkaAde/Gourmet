<?php

namespace App\Http\Controllers\Admin;

use App\Models\NumberTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class NumberTableController extends Controller
{
    public function index()
    {
        $tables = NumberTable::latest()->paginate(10);
        
        return view('admin.numbertables', [
            'title' => 'Table Management',
            'tables' => $tables
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:255|unique:number_tables,table_number',
            'table_capacity' => 'required|string|max:255'
        ]);

        NumberTable::create($validated);

        return redirect()->route('admin.tables.index')->with('success', 'Table created successfully!');
    }

    public function update(Request $request, NumberTable $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:255|unique:number_tables,table_number,'.$table->id,
            'table_capacity' => 'required|string|max:255'
        ]);

        $table->update($validated);

        return redirect()->route('admin.tables.index')->with('success', 'Table updated successfully!');
    }

    public function destroy(NumberTable $table)
{
    if ($table->reservations()->exists()) {
        return back()->with('error', 'Cannot delete table because it has associated reservations.');
    }

    if (Schema::hasColumn('orders', 'table_number')) {
        if ($table->orders()->exists()) {
            return back()->with('error', 'Cannot delete table because it has associated orders.');
        }
    }

    $table->delete();
    return redirect()->route('admin.tables.index')->with('success', 'Table deleted successfully!');
}
}