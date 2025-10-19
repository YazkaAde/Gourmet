<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\NumberTable;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create()
{
    $tables = NumberTable::all();
    $categories = Category::all();
    $menus = Menu::with(['category', 'reviews'])
        ->when(request('category'), function($query) {
            return $query->where('category_id', request('category'));
        })
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->paginate(8);

    return view('customer.reservation.create', compact('tables', 'menus', 'categories'));
}

    public function store(Request $request)
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        
        $validated = $request->validate([
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $time = Carbon::createFromFormat('H:i', $value, 'Asia/Jakarta');
                    $start = Carbon::createFromTime(9, 0, 0, 'Asia/Jakarta');
                    $end = Carbon::createFromTime(21, 0, 0, 'Asia/Jakarta');
                    
                    if ($time->lt($start) || $time->gt($end)) {
                        $fail('Waktu reservasi hanya tersedia dari jam 09:00 sampai 21:00');
                    }
                },
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    try {
                        $startTime = $request->reservation_time;
                        $endTime = $value;
                        
                        // Debug log
                        Log::info('Time Validation', [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'user_id' => auth()->id()
                        ]);
                        
                        list($startHour, $startMinute) = explode(':', $startTime);
                        list($endHour, $endMinute) = explode(':', $endTime);
                        
                        $startTotalMinutes = ($startHour * 60) + $startMinute;
                        $endTotalMinutes = ($endHour * 60) + $endMinute;
                        
                        $diffInMinutes = $endTotalMinutes - $startTotalMinutes;
                        
                        Log::info('Time Calculation', [
                            'start_total_minutes' => $startTotalMinutes,
                            'end_total_minutes' => $endTotalMinutes,
                            'diff_in_minutes' => $diffInMinutes
                        ]);
                        
                        if ($diffInMinutes < 60) {
                            $fail('Waktu berakhir reservasi harus minimal 1 jam dari waktu mulai');
                        }
                        
                        if ($endTotalMinutes > (21 * 60)) {
                            $fail('Waktu berakhir reservasi tidak boleh melebihi jam 21:00');
                        }
                        
                    } catch (\Exception $e) {
                        Log::error('Time validation error: ' . $e->getMessage());
                        $fail('Terjadi kesalahan dalam validasi waktu');
                    }
                },
            ],
            'guest_count' => 'required|integer|min:1|max:100',
            'table_number' => 'required|exists:number_tables,table_number',
            'notes' => 'nullable|string|max:500',
            'menu_items' => 'nullable|array',
            'menu_items.*.menu_id' => 'required_with:menu_items|exists:menus,id',
            'menu_items.*.quantity' => 'required_with:menu_items|integer|min:1',
        ]);

        $isTableAvailable = !Reservation::where('table_number', $validated['table_number'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('reservation_time', [$validated['reservation_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['reservation_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('reservation_time', '<=', $validated['reservation_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if (!$isTableAvailable) {
            return back()->withErrors(['table_number' => 'Meja tidak tersedia pada tanggal dan waktu yang dipilih.'])->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'end_time' => $validated['end_time'],
            'guest_count' => $validated['guest_count'],
            'table_number' => $validated['table_number'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        if (!empty($validated['menu_items'])) {
            foreach ($validated['menu_items'] as $menuItem) {
                $menu = \App\Models\Menu::find($menuItem['menu_id']);
                OrderItem::create([
                    'reservation_id' => $reservation->id,
                    'menu_id' => $menuItem['menu_id'],
                    'quantity' => $menuItem['quantity'],
                    'price' => $menu->price,
                    'total_price' => $menu->price * $menuItem['quantity']
                ]);
            }
        }

        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil dibuat. Silakan lakukan pembayaran DP untuk konfirmasi.');
    }

    public function show(Reservation $reservation)
{
    if ($reservation->user_id !== Auth::id()) {
        abort(403);
    }

    $reservation->load(['table', 'orderItems.menu', 'payments', 'user', 'orders.orderItems.menu']);
    
    $categories = Category::all();
    
    $menus = Menu::with(['category', 'reviews'])
        ->when(request('category'), function($query) {
            return $query->where('category_id', request('category'));
        })
        ->withCount('reviews')
        ->withAvg('reviews', 'rating')
        ->paginate(8);

    $reservation->checkAndUpdateStatus();
    $reservation->refresh();

    return view('customer.reservation.show', compact('reservation', 'menus', 'categories'));
}

    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with(['table', 'orderItems.menu', 'payments', 'orders'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        foreach ($reservations as $reservation) {
            $reservation->checkAndUpdateStatus();
        }

        return view('customer.reservation.index', compact('reservations'));
    }
    
    public function edit(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || $reservation->status !== 'pending') {
            abort(403);
        }

        $tables = NumberTable::all();
        $categories = Category::all();
        $menus = Menu::with(['category', 'reviews'])
            ->when(request('category'), function($query) {
                return $query->where('category_id', request('category'));
            })
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(8);
        
        return view('customer.reservation.edit', compact('reservation', 'tables', 'menus', 'categories'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || $reservation->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $time = Carbon::createFromFormat('H:i', $value);
                    $start = Carbon::createFromTime(9, 0);
                    $end = Carbon::createFromTime(21, 0);
                    
                    if ($time->lt($start) || $time->gt($end)) {
                        $fail('Waktu reservasi hanya tersedia dari jam 09:00 sampai 21:00');
                    }
                },
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    try {
                        $startTime = $request->reservation_time;
                        $endTime = $value;
                        
                        Log::info('Time Validation Update', [
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'user_id' => auth()->id()
                        ]);
                        
                        list($startHour, $startMinute) = explode(':', $startTime);
                        list($endHour, $endMinute) = explode(':', $endTime);
                        
                        $startTotalMinutes = ($startHour * 60) + $startMinute;
                        $endTotalMinutes = ($endHour * 60) + $endMinute;
                        
                        $diffInMinutes = $endTotalMinutes - $startTotalMinutes;
                        
                        Log::info('Time Calculation Update', [
                            'start_total_minutes' => $startTotalMinutes,
                            'end_total_minutes' => $endTotalMinutes,
                            'diff_in_minutes' => $diffInMinutes
                        ]);
                        
                        if ($diffInMinutes < 60) {
                            $fail('Waktu berakhir reservasi harus minimal 1 jam dari waktu mulai');
                        }
                        
                        if ($endTotalMinutes > (21 * 60)) {
                            $fail('Waktu berakhir reservasi tidak boleh melebihi jam 21:00');
                        }
                        
                    } catch (\Exception $e) {
                        Log::error('Time validation error in update: ' . $e->getMessage());
                        $fail('Terjadi kesalahan dalam validasi waktu');
                    }
                },
            ],
            'guest_count' => 'required|integer|min:1|max:100',
            'table_number' => 'required|exists:number_tables,table_number',
            'notes' => 'nullable|string|max:500',
        ]);

        $isTableAvailable = !Reservation::where('table_number', $validated['table_number'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where('id', '!=', $reservation->id)
            ->where(function($query) use ($validated) {
                $query->whereBetween('reservation_time', [$validated['reservation_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['reservation_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('reservation_time', '<=', $validated['reservation_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if (!$isTableAvailable) {
            return back()->withErrors(['table_number' => 'Meja tidak tersedia pada tanggal dan waktu yang dipilih.'])->withInput();
        }

        $reservation->update($validated);

        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function addMenu(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || in_array($reservation->status, ['completed', 'cancelled'])) {
            abort(403);
        }
        
        $categories = Category::all();
        $menus = Menu::with('category')->paginate(12);
        
        if (request()->ajax() || request()->wantsJson() || request()->hasHeader('X-Requested-With')) {
            return view('customer.reservation.partials.add-menu-modal', compact('reservation', 'menus'));
        }
        
        return redirect()->route('customer.reservations.show', $reservation)
        ->with('info', 'Use the "Add Menu Items" button to add menu items.');
    }

    public function updateMenuItems(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->isMenuEditable()) {
            return response()->json([
                'success' => false, 
                'message' => 'Menu items cannot be modified at this time'
            ], 403);
        }

        $validated = $request->validate([
            'updates' => 'required|array',
            'updates.*.itemId' => 'required|exists:order_items,id',
            'updates.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            foreach ($validated['updates'] as $update) {
                $orderItem = OrderItem::find($update['itemId']);
                if ($orderItem && $orderItem->reservation_id === $reservation->id) {
                    $orderItem->update([
                        'quantity' => $update['quantity'],
                        'total_price' => $orderItem->price * $update['quantity']
                    ]);
                }
            }

            $reservation->syncOrderItems();

            return response()->json([
                'success' => true, 
                'message' => 'Menu items updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error updating items: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeMenu(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->isMenuEditable()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Menu items cannot be modified at this time'
                ], 403);
            }
            abort(403);
        }

        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $existingItem = OrderItem::where('reservation_id', $reservation->id)
                ->where('menu_id', $validated['menu_id'])
                ->first();

            $menu = \App\Models\Menu::find($validated['menu_id']);
            
            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $validated['quantity'],
                    'total_price' => $menu->price * ($existingItem->quantity + $validated['quantity'])
                ]);
                $message = 'Menu item quantity updated successfully!';
            } else {
                OrderItem::create([
                    'reservation_id' => $reservation->id,
                    'menu_id' => $validated['menu_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $menu->price,
                    'total_price' => $menu->price * $validated['quantity']
                ]);
                $message = 'Menu item added successfully!';
            }

            $reservation->syncOrderItems();
            $reservation->refresh();

            if ($request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With')) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'updated_reservation' => [
                        'order_items_count' => $reservation->orderItems->count(),
                        'order_items' => $reservation->orderItems->map(function($item) {
                            return [
                                'menu_name' => $item->menu->name,
                                'quantity' => $item->quantity,
                                'total_price' => $item->total_price
                            ];
                        })->values(),
                        'menu_total' => $reservation->menu_total,
                        'total_amount' => $reservation->total_amount,
                        'down_payment_amount' => $reservation->down_payment_amount
                    ]
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function removeMenuItem(Reservation $reservation, OrderItem $orderItem)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->isMenuEditable()) {
            abort(403);
        }
    
        $orderItem->delete();
        $reservation->syncOrderItems();
    
        return back()->with('success', 'Menu item removed successfully');
    }

    public function clearMenu(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->isMenuEditable()) {
            abort(403);
        }
    
        OrderItem::where('reservation_id', $reservation->id)->delete();
    
        return back()->with('success', 'All menu items cleared');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status === 'cancelled') {
            return back()->with('error', 'Reservasi sudah dibatalkan.');
        }

        $cancellationFee = 0;
        if ($reservation->hasPreOrder()) {
            $cancellationFee = $reservation->cancellation_fee;
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('customer.reservations.index')
            ->with('success', 'Reservasi berhasil dibatalkan.' . ($cancellationFee > 0 ? 
                " Cancellation fee: Rp " . number_format($cancellationFee, 0) : ''));
    }

    public function getReservationsUpdates()
    {
        $user = Auth::user();
        
        $reservations = Reservation::where('user_id', $user->id)
            ->with(['table', 'orderItems.menu', 'payments'])
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get()
            ->map(function($reservation) {
                $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
                
                return [
                    'id' => $reservation->id,
                    'status' => $reservation->status,
                    'table_number' => $reservation->table_number,
                    'reservation_date' => $reservation->reservation_date->format('M d, Y'),
                    'reservation_time' => $reservation->reservation_time,
                    'total_amount' => $reservation->total_amount,
                    'total_paid' => $totalPaid,
                    'remaining_payment' => max($reservation->total_amount - $totalPaid, 0),
                    'items_count' => $reservation->orderItems->count(),
                    'payments_count' => $reservation->payments->count()
                ];
            });

        return response()->json([
            'reservations' => $reservations,
            'lastUpdate' => now()->toISOString()
        ]);
    }
}