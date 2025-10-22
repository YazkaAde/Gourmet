<?php

namespace App\Http\Controllers\Customer;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $orders = Order::with(['orderItems.menu', 'payment'])
            ->where('user_id', $user->id)
            ->whereNull('reservation_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $menuIds = collect();
        foreach ($orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                $menuIds->push($orderItem->menu_id);
            }
        }
        
        $userReviews = Review::where('user_id', $user->id)
            ->whereIn('menu_id', $menuIds->unique())
            ->get()
            ->keyBy('menu_id');
        
        return view('customer.orders.index', compact('orders', 'userReviews'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load([
            'orderItems.menu', 
            'orderItems.menu.reviews' => function($query) use ($order) {
                $query->where('user_id', auth()->id())
                    ->where('order_id', $order->id);
            }, 
            'reservation', 
            'table', 
            'payment'
        ]);
        
        return view('customer.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status == 'completed' || $order->status == 'cancelled') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'This order cannot be edited as it is already ' . $order->status . '.');
        }

        $order->load(['orderItems.menu', 'payment']);
        
        $categories = \App\Models\Category::all();
        $menus = \App\Models\Menu::with(['category', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('status', 'available')
            ->get();

        $tables = \App\Models\NumberTable::all();

        return view('customer.orders.edit', compact('order', 'categories', 'menus', 'tables'));
    }

    public function update(Order $order, Request $request)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status == 'completed' || $order->status == 'cancelled') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'This order cannot be edited as it is already ' . $order->status . '.');
        }

        $request->validate([
            'order_type' => 'required|in:dine_in,take_away',
            'table_number' => 'required_if:order_type,dine_in|nullable|exists:number_tables,table_number',
            'notes' => 'nullable|string|max:500',
            'order_items' => 'sometimes|array',
            'order_items.*.id' => 'required|exists:order_items,id',
            'order_items.*.quantity' => 'required|integer|min:1|max:10',
            'items_to_remove' => 'sometimes|array',
            'items_to_remove.*' => 'exists:order_items,id',
        ]);

        try {
            \DB::beginTransaction();

            $order->update([
                'order_type' => $request->order_type,
                'table_number' => $request->order_type == 'dine_in' ? $request->table_number : null,
                'notes' => $request->notes,
            ]);

            $totalPrice = 0;

            if ($order->status == 'pending' && $request->has('items_to_remove')) {
                foreach ($request->items_to_remove as $itemId) {
                    $order->orderItems()->where('id', $itemId)->delete();
                }
            }

            if ($request->has('order_items')) {
                foreach ($request->order_items as $itemData) {
                    $orderItem = $order->orderItems()->where('id', $itemData['id'])->first();
                    
                    if ($orderItem) {
                        if ($order->status == 'processing' && $itemData['quantity'] < $orderItem->quantity) {
                            throw new \Exception('Cannot reduce quantity for items that are already being processed.');
                        }

                        $orderItem->update([
                            'quantity' => $itemData['quantity'],
                            'total_price' => $orderItem->price * $itemData['quantity']
                        ]);
                        $totalPrice += $orderItem->total_price;
                    }
                }
            }

            $order->update(['total_price' => $totalPrice]);

            \DB::commit();

            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Order updated successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cancel(Order $order, Request $request) 
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel order that is already being processed.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders.index')->with('success', 'Order cancelled successfully.');
    }

    public function showPayFromReservation(Order $order)
    {
        if ($order->user_id !== Auth::id() || !$order->reservation_id) {
            abort(403);
        }
        
        $reservation = $order->reservation;
        
        if ($reservation->status !== 'confirmed') {
            return back()->with('error', 'Reservation is not confirmed yet.');
        }
        
        $totalPaidForReservation = $reservation->payments()->where('status', 'paid')->sum('amount');
        $reservationFee = $reservation->reservation_fee;
        $paidForMenu = max($totalPaidForReservation - $reservationFee, 0);
        
        $remainingPayment = max($order->total_price - $paidForMenu, 0);
        
        if ($remainingPayment <= 0) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('info', 'Order has been fully paid.');
        }
        
        return view('customer.orders.pay-from-reservation', compact('order', 'reservation', 'remainingPayment', 'paidForMenu'));
    }

    public function payFromReservation(Order $order, Request $request)
    {
        if ($order->user_id !== Auth::id() || !$order->reservation_id) {
            abort(403);
        }
        
        $reservation = $order->reservation;
        
        if ($reservation->status !== 'confirmed') {
            return back()->with('error', 'Reservation is not confirmed yet.');
        }
        
        $totalPaidForReservation = $reservation->payments()->where('status', 'paid')->sum('amount');
        $reservationFee = $reservation->reservation_fee;
        $paidForMenu = max($totalPaidForReservation - $reservationFee, 0);
        
        $remainingPayment = max($order->total_price - $paidForMenu, 0);
        
        if ($remainingPayment <= 0) {
            return back()->with('info', 'Order has been fully paid.');
        }
        
        $request->validate([
            'payment_method' => 'required|in:cash,credit_card,debit_card,qris,bank_transfer',
            'amount_paid' => [
                'required',
                'numeric',
                'min:' . $remainingPayment,
                'max:' . $remainingPayment
            ],
        ]);
        
        $paymentData = [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
            'notes' => 'Payment for order from reservation #' . $reservation->id,
        ];
        
        if ($request->payment_method === 'cash') {
            $paymentData['amount_paid'] = $request->amount_paid;
            $paymentData['change'] = 0;
        }
        
        $payment = Payment::create($paymentData);
        
        if ($payment->status === 'paid') {
            $order->update(['status' => 'completed']);
            
            $this->checkReservationCompletion($reservation);
        }
        
        return redirect()->route('customer.reservations.show', $order->reservation)
            ->with('success', 'Payment processed successfully.');
    }

    private function checkReservationCompletion(Reservation $reservation)
    {
        $allOrdersCompleted = $reservation->orders()
            ->where('status', '!=', 'completed')
            ->doesntExist();
        
        if ($allOrdersCompleted) {
            $reservation->update(['status' => 'completed']);
        }
    }

    public function getOrdersUpdates()
    {
        $user = Auth::user();
        
        $orders = Order::with(['orderItems.menu', 'payment'])
            ->where('user_id', $user->id)
            ->whereNull('reservation_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'payment_status' => $order->payment ? $order->payment->status : null,
                    'created_at' => $order->created_at->format('M d, Y H:i'),
                    'items_count' => $order->orderItems->count()
                ];
            });

        return response()->json([
            'orders' => $orders,
            'lastUpdate' => now()->toISOString()
        ]);
    }

    public function addItems(Order $order, Request $request)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($order->status == 'completed' || $order->status == 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be modified as it is already ' . $order->status . '.'
            ], 400);
        }

        $request->validate([
            'new_items' => 'required|array',
            'new_items.*.menu_id' => 'required|exists:menus,id',
            'new_items.*.quantity' => 'required|integer|min:1|max:10',
        ]);

        try {
            \DB::beginTransaction();

            $totalAdditionalPrice = 0;

            foreach ($request->new_items as $itemData) {
                $existingItem = $order->orderItems()
                    ->where('menu_id', $itemData['menu_id'])
                    ->first();

                if ($existingItem) {
                    $newQuantity = $existingItem->quantity + $itemData['quantity'];
                    if ($newQuantity > 10) {
                        throw new \Exception('Maximum quantity per item is 10');
                    }

                    $existingItem->update([
                        'quantity' => $newQuantity,
                        'total_price' => $existingItem->price * $newQuantity
                    ]);
                    $totalAdditionalPrice += $existingItem->price * $itemData['quantity'];
                } else {
                    $menu = \App\Models\Menu::find($itemData['menu_id']);
                    $orderItem = $order->orderItems()->create([
                        'menu_id' => $itemData['menu_id'],
                        'quantity' => $itemData['quantity'],
                        'price' => $menu->price,
                        'total_price' => $menu->price * $itemData['quantity']
                    ]);
                    $totalAdditionalPrice += $orderItem->total_price;
                }
            }

            $order->update([
                'total_price' => $order->total_price + $totalAdditionalPrice
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Items added to order successfully.',
                'order_total' => $order->total_price
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add items: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAddItemsModal(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $categories = Category::all();
        
        $menus = Menu::with('category')
            ->where('status', 'available')
            ->paginate(12);
        
        return view('customer.orders.partials.add-items-modal', compact('order', 'menus', 'categories'));
    }
}