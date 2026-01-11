<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stock(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by supplier
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by stock status
        if ($request->stock_status) {
            if ($request->stock_status == 'low') {
                $query->whereRaw('stock <= min_stock');
            } elseif ($request->stock_status == 'out') {
                $query->where('stock', 0);
            } elseif ($request->stock_status == 'available') {
                $query->whereRaw('stock > min_stock');
            }
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        // Summary
        $summary = [
            'total_products' => Product::count(),
            'low_stock' => Product::whereRaw('stock <= min_stock')->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'total_stock_value' => Product::sum(DB::raw('stock * purchase_price')),
            'total_potential_value' => Product::sum(DB::raw('stock * selling_price')),
        ];

        return view('reports.stock', compact('products', 'categories', 'suppliers', 'summary'));
    }

    public function sales(Request $request)
    {
        $dateFrom = $request->date_from ?: now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?: now()->format('Y-m-d');

        $query = StockOut::with(['items.product', 'user'])
            ->whereBetween('date', [$dateFrom, $dateTo]);

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        $stockOuts = $query->latest('date')->get();

        // Calculate totals
        $totalSales = $stockOuts->sum('total');
        $totalTransactions = $stockOuts->count();

        // Calculate profit
        $totalProfit = 0;
        $totalModal = 0;

        foreach ($stockOuts as $stockOut) {
            foreach ($stockOut->items as $item) {
                $modal = $item->quantity * $item->product->purchase_price;
                $totalModal += $modal;
            }
        }

        $totalProfit = $totalSales - $totalModal;

        // Best selling products
        $bestSelling = DB::table('stock_out_items')
            ->join('products', 'stock_out_items.product_id', '=', 'products.id')
            ->join('stock_outs', 'stock_out_items.stock_out_id', '=', 'stock_outs.id')
            ->whereBetween('stock_outs.date', [$dateFrom, $dateTo])
            ->select(
                'products.name',
                'products.code',
                DB::raw('SUM(stock_out_items.quantity) as total_qty'),
                DB::raw('SUM(stock_out_items.subtotal) as total_amount')
            )
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $summary = [
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'total_modal' => $totalModal,
            'total_profit' => $totalProfit,
            'avg_transaction' => $totalTransactions > 0 ? $totalSales / $totalTransactions : 0,
        ];

        return view('reports.sales', compact('stockOuts', 'summary', 'bestSelling', 'dateFrom', 'dateTo'));
    }

    public function purchases(Request $request)
    {
        $dateFrom = $request->date_from ?: now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?: now()->format('Y-m-d');

        $query = StockIn::with(['supplier', 'items.product', 'user'])
            ->whereBetween('date', [$dateFrom, $dateTo]);

        // Filter by supplier
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Search
        if ($request->search) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        $stockIns = $query->latest('date')->get();
        $suppliers = Supplier::orderBy('name')->get();

        // Calculate totals
        $totalPurchases = $stockIns->sum('total');
        $totalTransactions = $stockIns->count();

        // Most purchased products
        $mostPurchased = DB::table('stock_in_items')
            ->join('products', 'stock_in_items.product_id', '=', 'products.id')
            ->join('stock_ins', 'stock_in_items.stock_in_id', '=', 'stock_ins.id')
            ->whereBetween('stock_ins.date', [$dateFrom, $dateTo])
            ->select(
                'products.name',
                'products.code',
                DB::raw('SUM(stock_in_items.quantity) as total_qty'),
                DB::raw('SUM(stock_in_items.subtotal) as total_amount')
            )
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // Top suppliers
        $topSuppliers = DB::table('stock_ins')
            ->join('suppliers', 'stock_ins.supplier_id', '=', 'suppliers.id')
            ->whereBetween('stock_ins.date', [$dateFrom, $dateTo])
            ->select(
                'suppliers.name',
                DB::raw('COUNT(stock_ins.id) as total_transactions'),
                DB::raw('SUM(stock_ins.total) as total_amount')
            )
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        $summary = [
            'total_purchases' => $totalPurchases,
            'total_transactions' => $totalTransactions,
            'avg_transaction' => $totalTransactions > 0 ? $totalPurchases / $totalTransactions : 0,
        ];

        return view('reports.purchases', compact('stockIns', 'suppliers', 'summary', 'mostPurchased', 'topSuppliers', 'dateFrom', 'dateTo'));
    }

    public function profit(Request $request)
    {
        $dateFrom = $request->date_from ?: now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?: now()->format('Y-m-d');

        // Get all sales in period
        $stockOuts = StockOut::with(['items.product'])
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();

        // Calculate detailed profit
        $profitData = [];
        $totalRevenue = 0;
        $totalCost = 0;
        $totalProfit = 0;

        foreach ($stockOuts as $stockOut) {
            $revenue = 0;
            $cost = 0;

            foreach ($stockOut->items as $item) {
                $itemRevenue = $item->quantity * $item->price;
                $itemCost = $item->quantity * $item->product->purchase_price;

                $revenue += $itemRevenue;
                $cost += $itemCost;
            }

            $profit = $revenue - $cost;

            $profitData[] = [
                'date' => $stockOut->date,
                'code' => $stockOut->code,
                'customer' => $stockOut->customer_name ?: 'Umum',
                'revenue' => $revenue,
                'cost' => $cost,
                'profit' => $profit,
                'margin' => $cost > 0 ? ($profit / $cost) * 100 : 0,
            ];

            $totalRevenue += $revenue;
            $totalCost += $cost;
            $totalProfit += $profit;
        }

        // Profit by category
        $profitByCategory = DB::table('stock_out_items')
            ->join('stock_outs', 'stock_out_items.stock_out_id', '=', 'stock_outs.id')
            ->join('products', 'stock_out_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('stock_outs.date', [$dateFrom, $dateTo])
            ->select(
                'categories.name',
                'categories.icon',
                DB::raw('SUM(stock_out_items.subtotal) as revenue'),
                DB::raw('SUM(stock_out_items.quantity * products.purchase_price) as cost')
            )
            ->groupBy('categories.id', 'categories.name', 'categories.icon')
            ->get()
            ->map(function($item) {
                $item->profit = $item->revenue - $item->cost;
                $item->margin = $item->cost > 0 ? ($item->profit / $item->cost) * 100 : 0;
                return $item;
            })
            ->sortByDesc('profit');

        $summary = [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'avg_margin' => $totalCost > 0 ? ($totalProfit / $totalCost) * 100 : 0,
            'total_transactions' => count($profitData),
        ];

        return view('reports.profit', compact('profitData', 'profitByCategory', 'summary', 'dateFrom', 'dateTo'));
    }
}
