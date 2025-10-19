<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category')
            ->orderBy('status')
            ->orderBy('name');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $menus = $query->paginate(12);
        $categories = Category::has('menus')->get();

        return view('cashier.menus.index', [
            'menus' => $menus,
            'categories' => $categories,
            'title' => 'Menu Management'
        ]);
    }

    public function toggleStatus(Menu $menu): JsonResponse
    {
        try {
            $newStatus = $menu->status == 'available' ? 'unavailable' : 'available';
            
            $menu->update([
                'status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu status updated successfully',
                'new_status' => $newStatus,
                'status_badge' => $this->getStatusBadge($newStatus),
                'status_text' => ucfirst($newStatus)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update menu status'
            ], 500);
        }
    }

    public function getStatusCounts(): JsonResponse
    {
        $availableCount = Menu::where('status', 'available')->count();
        $unavailableCount = Menu::where('status', 'unavailable')->count();
        $totalCount = Menu::count();

        return response()->json([
            'available' => $availableCount,
            'unavailable' => $unavailableCount,
            'total' => $totalCount
        ]);
    }

    private function getStatusBadge(string $status): string
    {
        if ($status == 'available') {
            return '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full border border-green-200 font-medium">Available</span>';
        } else {
            return '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full border border-red-200 font-medium">Unavailable</span>';
        }
    }
}