<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total data
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // Low stock products
        $lowStockProducts = Product::whereRaw('stock <= min_stock')->count();

        // Total stock value
        $totalStockValue = Product::sum(DB::raw('stock * purchase_price'));

        // Revenue this month
        $monthlyRevenue = StockOut::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('total');

        // Purchases this month
        $monthlyPurchases = StockIn::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('total');

        // Recent transactions
        $recentStockIns = StockIn::with('supplier')
            ->latest()
            ->take(5)
            ->get();

        $recentStockOuts = StockOut::latest()
            ->take(5)
            ->get();

        // Low stock products list
        $lowStockProductsList = Product::with(['category', 'supplier'])
            ->whereRaw('stock <= min_stock')
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();

        // Top selling products (based on stock out)
        $topProducts = Product::withCount(['stockOutItems as total_sold' => function($query) {
                $query->select(DB::raw('sum(quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Monthly chart data
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'income' => StockOut::whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->sum('total'),
                'expense' => StockIn::whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->sum('total'),
            ];
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockProducts',
            'totalStockValue',
            'monthlyRevenue',
            'monthlyPurchases',
            'recentStockIns',
            'recentStockOuts',
            'lowStockProductsList',
            'topProducts',
            'monthlyData'
        ));
    }
}
