<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOut;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary Statistics
        $totalProducts = Product::count();
        $lowStockCount = Product::whereRaw('stock <= min_stock')->count();

        // Today's Sales
        $todaySales = StockOut::whereDate('date', today())->sum('total');

        // Monthly Sales
        $monthlySales = StockOut::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total');

        // Low Stock Products (Top 10)
        $lowStockProducts = Product::with('category')
            ->whereRaw('stock <= min_stock')
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        // Recent Transactions (Last 10)
        $recentTransactions = StockOut::with('user')
            ->latest('date')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Sales Chart Data (Last 7 days)
        $salesDates = [];
        $salesAmounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesDates[] = $date->format('d M');

            $amount = StockOut::whereDate('date', $date->format('Y-m-d'))->sum('total');
            $salesAmounts[] = $amount;
        }

        // Category Sales Data (This Month)
        $categorySalesData = DB::table('stock_out_items')
            ->join('stock_outs', 'stock_out_items.stock_out_id', '=', 'stock_outs.id')
            ->join('products', 'stock_out_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereMonth('stock_outs.date', now()->month)
            ->whereYear('stock_outs.date', now()->year)
            ->select(
                'categories.name',
                'categories.icon',
                DB::raw('SUM(stock_out_items.subtotal) as total_sales')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.icon')
            ->orderByDesc('total_sales')
            ->limit(6)
            ->get();

        $categoryNames = $categorySalesData->map(function($item) {
            return $item->icon . ' ' . $item->name;
        })->toArray();

        $categorySales = $categorySalesData->pluck('total_sales')->toArray();

        return view('dashboard', compact(
            'totalProducts',
            'lowStockCount',
            'todaySales',
            'monthlySales',
            'lowStockProducts',
            'recentTransactions',
            'salesDates',
            'salesAmounts',
            'categoryNames',
            'categorySales'
        ));
    }
}
