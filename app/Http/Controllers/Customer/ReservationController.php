<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\NumberTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create()
    {
        $tables = NumberTable::all();
        $menus = \App\Models\Menu::all();
        return view('customer.reservation.create', compact('tables', 'menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $time = strtotime($value);
                    $start = strtotime('09:00');
                    $end = strtotime('21:00');
                    
                    if ($time < $start || $time > $end) {
                        $fail('Waktu reservasi hanya tersedia dari jam 09:00 sampai 21:00');
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
            ->where('reservation_time', $validated['reservation_time'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if (!$isTableAvailable) {
            return back()->withErrors(['table_number' => 'Meja tidak tersedia pada tanggal dan waktu yang dipilih.'])->withInput();
        }

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
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

        $reservation->checkAndUpdateStatus();
        $reservation->refresh();

        return view('customer.reservation.show', compact('reservation'));
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
        return view('customer.reservation.edit', compact('reservation', 'tables'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || $reservation->status !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i|after_or_equal:09:00|before_or_equal:21:00',
            'guest_count' => 'required|integer|min:1|max:100',
            'table_number' => 'required|exists:number_tables,table_number',
            'notes' => 'nullable|string|max:500',
        ]);

        $isTableAvailable = !Reservation::where('table_number', $validated['table_number'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where('reservation_time', $validated['reservation_time'])
            ->where('id', '!=', $reservation->id)
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
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $menus = \App\Models\Menu::paginate(10);
        return view('customer.reservation.add-menu', compact('reservation', 'menus'));
    }

    public function storeMenu(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = \App\Models\Menu::find($validated['menu_id']);
        
        OrderItem::create([
            'reservation_id' => $reservation->id,
            'menu_id' => $validated['menu_id'],
            'quantity' => $validated['quantity'],
            'price' => $menu->price,
            'total_price' => $menu->price * $validated['quantity']
        ]);

        return back()->with('success', 'Menu added to reservation');
    }

    public function clearMenu(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
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
}