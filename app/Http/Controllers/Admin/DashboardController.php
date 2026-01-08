<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các tham số filter
        $period = $request->get('period', 'month'); // day, week, month, year, custom
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Dữ liệu doanh thu (orders với status completed hoặc processing)
        $revenueData = $this->getRevenueData($period, $startDate, $endDate);
        
        // Dữ liệu nhập hàng (inventory_movements với type = 'purchase' hoặc 'in')
        $purchaseData = $this->getPurchaseData($period, $startDate, $endDate);

        // Thống kê tổng quan
        $stats = $this->getOverallStats($startDate, $endDate);

        // Thống kê tháng cao nhất/thấp nhất
        $monthlyStats = $this->getMonthlyStats();

        return view('admin.dashboard', compact(
            'revenueData',
            'purchaseData',
            'stats',
            'monthlyStats',
            'period',
            'startDate',
            'endDate'
        ));
    }

    private function getRevenueData($period, $startDate, $endDate)
    {
        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['completed', 'processing', 'shipped']);

        switch ($period) {
            case 'day':
                return $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            case 'week':
                return $query->select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('MIN(DATE(created_at)) as start_date'),
                    DB::raw('MAX(DATE(created_at)) as end_date'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('week')
                ->orderBy('week')
                ->get();

            case 'month':
                return $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            case 'year':
                return $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            default:
                return $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as revenue'),
                    DB::raw('COUNT(*) as order_count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }
    }

    private function getPurchaseData($period, $startDate, $endDate)
    {
        $query = DB::table('inventory_movements')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'purchase')
            ->whereNotNull('unit_cost');

        switch ($period) {
            case 'day':
                return $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(ABS(change_qty) * unit_cost) as total_cost'),
                    DB::raw('SUM(ABS(change_qty)) as total_quantity')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            case 'week':
                return $query->select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('MIN(DATE(created_at)) as start_date'),
                    DB::raw('SUM(ABS(change_qty) * unit_cost) as total_cost'),
                    DB::raw('SUM(ABS(change_qty)) as total_quantity')
                )
                ->groupBy('week')
                ->orderBy('week')
                ->get();

            case 'month':
                return $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(ABS(change_qty) * unit_cost) as total_cost'),
                    DB::raw('SUM(ABS(change_qty)) as total_quantity')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            case 'year':
                return $query->select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(ABS(change_qty) * unit_cost) as total_cost'),
                    DB::raw('SUM(ABS(change_qty)) as total_quantity')
                )
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            default:
                return $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(ABS(change_qty) * unit_cost) as total_cost'),
                    DB::raw('SUM(ABS(change_qty)) as total_quantity')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }
    }

    private function getOverallStats($startDate, $endDate)
    {
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['completed', 'processing', 'shipped'])
            ->sum('total');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['completed', 'processing', 'shipped'])
            ->count();

        // Calculate total purchase cost from inventory movements
        $totalPurchase = DB::table('inventory_movements')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'purchase')
            ->whereNotNull('unit_cost')
            ->selectRaw('SUM(ABS(change_qty) * unit_cost) as total')
            ->value('total') ?? 0;

        $profit = $totalRevenue - $totalPurchase;

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_purchase' => $totalPurchase,
            'profit' => $profit,
            'avg_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
        ];
    }

    private function getMonthlyStats()
    {
        // Thống kê 12 tháng gần nhất
        $monthlyRevenue = Order::where('created_at', '>=', now()->subMonths(12))
            ->whereIn('status', ['completed', 'processing', 'shipped'])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('revenue', 'desc')
            ->get();

        $monthlyPurchase = DB::table('inventory_movements')
            ->where('created_at', '>=', now()->subMonths(12))
            ->where('type', 'purchase')
            ->whereNotNull('unit_cost')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(ABS(change_qty) * unit_cost) as total_cost')
            )
            ->groupBy('year', 'month')
            ->orderBy('total_cost', 'desc')
            ->get();

        return [
            'highest_revenue_month' => $monthlyRevenue->first(),
            'lowest_revenue_month' => $monthlyRevenue->last(),
            'highest_purchase_month' => $monthlyPurchase->first(),
            'lowest_purchase_month' => $monthlyPurchase->last(),
        ];
    }
}

