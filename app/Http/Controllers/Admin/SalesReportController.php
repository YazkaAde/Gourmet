<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $today = now();
        $type = $request->get('type', 'daily');
        
        $ordersQuery = Order::with(['user', 'payment']);
        
        switch ($type) {
            case 'daily':
                $ordersQuery->whereDate('created_at', $today->toDateString());
                break;
            case 'weekly':
                $ordersQuery->whereBetween('created_at', [
                    $today->copy()->startOfWeek(), 
                    $today->copy()->endOfWeek()
                ]);
                break;
            case 'monthly':
                $ordersQuery->whereBetween('created_at', [
                    $today->copy()->startOfMonth(), 
                    $today->copy()->endOfMonth()
                ]);
                break;
            case 'yearly':
                $ordersQuery->whereBetween('created_at', [
                    $today->copy()->startOfYear(), 
                    $today->copy()->endOfYear()
                ]);
                break;
            default:
                break;
        }

        $orders = $ordersQuery->latest()->get();
        
        $totalOrders = $orders->count();
        
        $totalRevenue = $orders->sum(function($order) {
            return $order->payment && $order->payment->status === 'paid' 
                ? $order->total_price 
                : 0;
        });

        return view('admin.sales-report.index', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'type'
        ));
    }

    public function export(Request $request)
    {
        try {
            $type = $request->get('type', 'daily');
            $today = now();
            $query = Order::with(['user', 'payment', 'orderItems.menu']);

            switch ($type) {
                case 'daily':
                    $query->whereDate('created_at', $today->toDateString());
                    $title = "Daily Sales Report - " . $today->format('F d, Y');
                    break;
                case 'weekly':
                    $query->whereBetween('created_at', [
                        $today->copy()->startOfWeek(), 
                        $today->copy()->endOfWeek()
                    ]);
                    $title = "Weekly Sales Report - " . $today->copy()->startOfWeek()->format('M d') . ' to ' . $today->copy()->endOfWeek()->format('M d, Y');
                    break;
                case 'monthly':
                    $query->whereBetween('created_at', [
                        $today->copy()->startOfMonth(), 
                        $today->copy()->endOfMonth()
                    ]);
                    $title = "Monthly Sales Report - " . $today->format('F Y');
                    break;
                case 'yearly':
                    $query->whereBetween('created_at', [
                        $today->copy()->startOfYear(), 
                        $today->copy()->endOfYear()
                    ]);
                    $title = "Yearly Sales Report - " . $today->format('Y');
                    break;
                default:
                    $title = "Complete Sales Report - All Time";
                    break;
            }

            $orders = $query->latest()->get();
            
            $totalRevenue = $orders->sum(function($order) {
                return $order->payment && $order->payment->status === 'paid' 
                    ? $order->total_price 
                    : 0;
            });

            $data = [
                'orders' => $orders,
                'title' => $title,
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $orders->count(),
                'exportDate' => $today->format('F d, Y H:i:s'),
                'type' => $type
            ];

            $pdf = Pdf::loadView('admin.sales-report.export-pdf', $data);
            
            return $pdf->download("sales-report-{$type}-" . $today->format('Y-m-d') . '.pdf');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.sales-report.index')
                ->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $type = $request->get('type', 'custom');
        
        $query = Order::with(['user', 'payment', 'orderItems.menu']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(), 
                Carbon::parse($endDate)->endOfDay()
            ]);
        }
        
        $orders = $query->latest()->get();
        
        $totalOrders = $orders->count();
        
        $totalRevenue = $orders->sum(function($order) {
            return $order->payment && $order->payment->status === 'paid' 
                ? $order->total_price 
                : 0;
        });
    
        return view('admin.sales-report.index', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'type'
        ));
    }
    
    public function changePeriod(Request $request)
    {
        $type = $request->get('type', 'daily');
        return redirect()->route('admin.sales-report.index', ['type' => $type]);
    }
}