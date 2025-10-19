<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category')
            ->orderBy('status', 'asc')
            ->orderBy('order_count', 'desc');
        
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $menus = $query->paginate(12);
        $categories = Category::has('menus')->get();

        return view('customer.menu', [
            'menus' => $menus,
            'categories' => $categories,
            'title' => 'Our Menu'
        ]);
    }
}