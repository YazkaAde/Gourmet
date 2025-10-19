<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Review;
use App\Models\Payment;
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

    public function cancel(Order $order)
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
}