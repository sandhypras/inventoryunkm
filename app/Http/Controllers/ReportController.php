<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Category;
use App\Models\Setting;
use App\Mail\ReportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_products' => Product::count(),
            'low_stock' => Product::where('stock', '<=', DB::raw('min_stock'))->count(),
            'total_stock_value' => Product::sum(DB::raw('stock * purchase_price')),
            'total_sales' => StockOut::sum('total'),
            'total_purchases' => StockIn::sum('total'),
        ];

        return view('reports.index', compact('summary'));
    }

    public function stock(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->where('stock', '<=', DB::raw('min_stock'));
            } elseif ($request->status === 'out') {
                $query->where('stock', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->paginate(50);
        $categories = Category::all();

        return view('reports.stock', compact('products', 'categories'));
    }

    public function stockPdf(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        // Apply same filters as stock method
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->where('stock', '<=', DB::raw('min_stock'));
            } elseif ($request->status === 'out') {
                $query->where('stock', 0);
            }
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->get();

        $data = [
            'products' => $products,
            'title' => 'Laporan Stok Barang',
            'date' => date('d F Y'),
            'total_items' => $products->count(),
            'total_value' => $products->sum(function ($p) {
                return $p->stock * $p->purchase_price;
            }),
        ];

        $pdf = Pdf::loadView('reports.pdf.stock', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
    }

    public function emailStock(Request $request)
    {
        $request->validate([
            'owner_email' => 'required|email',
        ]);

        try {
            // Get products data
            $query = Product::with(['category', 'supplier']);

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            if ($request->filled('status')) {
                if ($request->status === 'low') {
                    $query->where('stock', '<=', DB::raw('min_stock'));
                } elseif ($request->status === 'out') {
                    $query->where('stock', 0);
                }
            }

            $products = $query->orderBy('name')->get();

            // Generate PDF
            $data = [
                'products' => $products,
                'title' => 'Laporan Stok Barang',
                'date' => date('d F Y'),
                'total_items' => $products->count(),
                'total_value' => $products->sum(function ($p) {
                    return $p->stock * $p->purchase_price;
                }),
            ];

            $pdf = Pdf::loadView('reports.pdf.stock', $data)
                ->setPaper('a4', 'landscape');

            // Save temporary PDF
            $filename = 'laporan-stok-' . date('Y-m-d-His') . '.pdf';
            $path = storage_path('app/temp/' . $filename);

            // Create temp directory if not exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($path);

            // Prepare email data
            $reportData = [
                'title' => 'Laporan Stok Barang',
                'type_label' => 'Laporan Stok',
                'owner_name' => Setting::get('owner_name', 'Owner'),
                'total_items' => $products->count(),
                'grand_total' => 'Rp ' . number_format($data['total_value'], 0, ',', '.'),
            ];

            // Send email
            Mail::to($request->owner_email)
                ->send(new ReportMail('stock', $reportData, $path));

            // Delete temporary file
            if (file_exists($path)) {
                unlink($path);
            }

            return redirect()->back()->with('success', 'Laporan berhasil dikirim ke ' . $request->owner_email);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function sales(Request $request)
    {
        $query = StockOut::with(['items.product']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(20);

        $summary = [
            'total_transactions' => $query->count(),
            'total_sales' => $query->sum('total'),
            'total_items' => $transactions->sum(function ($t) {
                return $t->items->sum('quantity');
            }),
        ];

        return view('reports.sales', compact('transactions', 'summary'));
    }

    public function salesPdf(Request $request)
    {
        $query = StockOut::with(['items.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $data = [
            'transactions' => $transactions,
            'title' => 'Laporan Penjualan',
            'date' => date('d F Y'),
            'date_range' => ($request->start_date && $request->end_date)
                ? date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y', strtotime($request->end_date))
                : 'Semua',
            'total_sales' => $transactions->sum('total'),
        ];

        $pdf = Pdf::loadView('reports.pdf.sales', $data);

        return $pdf->download('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function emailSales(Request $request)
    {
        $request->validate([
            'owner_email' => 'required|email',
        ]);

        try {
            $query = StockOut::with(['items.product']);

            if ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }

            $transactions = $query->orderBy('date', 'desc')->get();

            $dateRange = ($request->start_date && $request->end_date)
                ? date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y', strtotime($request->end_date))
                : 'Semua Periode';

            $data = [
                'transactions' => $transactions,
                'title' => 'Laporan Penjualan',
                'date' => date('d F Y'),
                'date_range' => $dateRange,
                'total_sales' => $transactions->sum('total'),
            ];

            $pdf = Pdf::loadView('reports.pdf.sales', $data);

            $filename = 'laporan-penjualan-' . date('Y-m-d-His') . '.pdf';
            $path = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($path);

            $reportData = [
                'title' => 'Laporan Penjualan',
                'type_label' => 'Laporan Penjualan',
                'owner_name' => Setting::get('owner_name', 'Owner'),
                'date_range' => $dateRange,
                'total_items' => $transactions->count() . ' transaksi',
                'grand_total' => 'Rp ' . number_format($data['total_sales'], 0, ',', '.'),
            ];

            Mail::to($request->owner_email)
                ->send(new ReportMail('sales', $reportData, $path, $dateRange));

            if (file_exists($path)) {
                unlink($path);
            }

            return redirect()->back()->with('success', 'Laporan penjualan berhasil dikirim ke ' . $request->owner_email);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function purchases(Request $request)
    {
        $query = StockIn::with(['supplier', 'items.product']);

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Supplier filter
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(20);
        $suppliers = \App\Models\Supplier::all();

        $summary = [
            'total_transactions' => $query->count(),
            'total_purchases' => $query->sum('total'),
            'total_items' => $transactions->sum(function ($t) {
                return $t->items->sum('quantity');
            }),
        ];

        return view('reports.purchases', compact('transactions', 'summary', 'suppliers'));
    }

    public function purchasesPdf(Request $request)
    {
        $query = StockIn::with(['supplier', 'items.product']);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $dateRange = ($request->start_date && $request->end_date)
            ? date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y', strtotime($request->end_date))
            : 'Semua';

        $data = [
            'transactions' => $transactions,
            'title' => 'Laporan Pembelian',
            'date' => date('d F Y'),
            'date_range' => $dateRange,
            'total_purchases' => $transactions->sum('total'),
        ];

        $pdf = Pdf::loadView('reports.pdf.purchases', $data);

        return $pdf->download('laporan-pembelian-' . date('Y-m-d') . '.pdf');
    }

    public function emailPurchases(Request $request)
    {
        $request->validate([
            'owner_email' => 'required|email',
        ]);

        try {
            $query = StockIn::with(['supplier', 'items.product']);

            if ($request->filled('start_date')) {
                $query->whereDate('date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('date', '<=', $request->end_date);
            }

            if ($request->filled('supplier')) {
                $query->where('supplier_id', $request->supplier);
            }

            $transactions = $query->orderBy('date', 'desc')->get();

            $dateRange = ($request->start_date && $request->end_date)
                ? date('d M Y', strtotime($request->start_date)) . ' - ' . date('d M Y', strtotime($request->end_date))
                : 'Semua Periode';

            $data = [
                'transactions' => $transactions,
                'title' => 'Laporan Pembelian',
                'date' => date('d F Y'),
                'date_range' => $dateRange,
                'total_purchases' => $transactions->sum('total'),
            ];

            $pdf = Pdf::loadView('reports.pdf.purchases', $data);

            $filename = 'laporan-pembelian-' . date('Y-m-d-His') . '.pdf';
            $path = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($path);

            $reportData = [
                'title' => 'Laporan Pembelian',
                'type_label' => 'Laporan Pembelian',
                'owner_name' => Setting::get('owner_name', 'Owner'),
                'date_range' => $dateRange,
                'total_items' => $transactions->count() . ' transaksi',
                'grand_total' => 'Rp ' . number_format($data['total_purchases'], 0, ',', '.'),
            ];

            Mail::to($request->owner_email)
                ->send(new ReportMail('purchases', $reportData, $path, $dateRange));

            if (file_exists($path)) {
                unlink($path);
            }

            return redirect()->back()->with('success', 'Laporan pembelian berhasil dikirim ke ' . $request->owner_email);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function profit(Request $request)
    {
        $startDate = $request->filled('start_date') ? $request->start_date : now()->subDays(30)->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        // Get sales data
        $sales = StockOut::with(['items.product'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        // Get purchases data
        $purchases = StockIn::with(['items.product'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        // Calculate totals
        $totalSales = $sales->sum('total');
        $totalPurchases = $purchases->sum('total');
        $grossProfit = $totalSales - $totalPurchases;
        $profitMargin = $totalSales > 0 ? ($grossProfit / $totalSales) * 100 : 0;

        // Product-wise profit
        $productProfits = [];
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $productId = $item->product_id;
                if (!isset($productProfits[$productId])) {
                    $productProfits[$productId] = [
                        'product' => $item->product,
                        'quantity_sold' => 0,
                        'revenue' => 0,
                        'cost' => 0,
                        'profit' => 0,
                    ];
                }
                $productProfits[$productId]['quantity_sold'] += $item->quantity;
                $productProfits[$productId]['revenue'] += $item->subtotal;
                $productProfits[$productId]['cost'] += $item->quantity * $item->product->purchase_price;
                $productProfits[$productId]['profit'] = $productProfits[$productId]['revenue'] - $productProfits[$productId]['cost'];
            }
        }

        // Sort by profit
        usort($productProfits, function($a, $b) {
            return $b['profit'] <=> $a['profit'];
        });

        $summary = [
            'total_sales' => $totalSales,
            'total_purchases' => $totalPurchases,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        return view('reports.profit', compact('summary', 'productProfits'));
    }

    public function profitPdf(Request $request)
    {
        $startDate = $request->filled('start_date') ? $request->start_date : now()->subDays(30)->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        $sales = StockOut::with(['items.product'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $purchases = StockIn::with(['items.product'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->get();

        $totalSales = $sales->sum('total');
        $totalPurchases = $purchases->sum('total');
        $grossProfit = $totalSales - $totalPurchases;
        $profitMargin = $totalSales > 0 ? ($grossProfit / $totalSales) * 100 : 0;

        $productProfits = [];
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $productId = $item->product_id;
                if (!isset($productProfits[$productId])) {
                    $productProfits[$productId] = [
                        'product' => $item->product,
                        'quantity_sold' => 0,
                        'revenue' => 0,
                        'cost' => 0,
                        'profit' => 0,
                    ];
                }
                $productProfits[$productId]['quantity_sold'] += $item->quantity;
                $productProfits[$productId]['revenue'] += $item->subtotal;
                $productProfits[$productId]['cost'] += $item->quantity * $item->product->purchase_price;
                $productProfits[$productId]['profit'] = $productProfits[$productId]['revenue'] - $productProfits[$productId]['cost'];
            }
        }

        usort($productProfits, function($a, $b) {
            return $b['profit'] <=> $a['profit'];
        });

        $dateRange = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));

        $data = [
            'title' => 'Laporan Keuntungan',
            'date' => date('d F Y'),
            'date_range' => $dateRange,
            'total_sales' => $totalSales,
            'total_purchases' => $totalPurchases,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'product_profits' => $productProfits,
        ];

        $pdf = Pdf::loadView('reports.pdf.profit', $data);

        return $pdf->download('laporan-keuntungan-' . date('Y-m-d') . '.pdf');
    }

    public function emailProfit(Request $request)
    {
        $request->validate([
            'owner_email' => 'required|email',
        ]);

        try {
            $startDate = $request->filled('start_date') ? $request->start_date : now()->subDays(30)->format('Y-m-d');
            $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

            $sales = StockOut::with(['items.product'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->get();

            $purchases = StockIn::with(['items.product'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate)
                ->get();

            $totalSales = $sales->sum('total');
            $totalPurchases = $purchases->sum('total');
            $grossProfit = $totalSales - $totalPurchases;
            $profitMargin = $totalSales > 0 ? ($grossProfit / $totalSales) * 100 : 0;

            $productProfits = [];
            foreach ($sales as $sale) {
                foreach ($sale->items as $item) {
                    $productId = $item->product_id;
                    if (!isset($productProfits[$productId])) {
                        $productProfits[$productId] = [
                            'product' => $item->product,
                            'quantity_sold' => 0,
                            'revenue' => 0,
                            'cost' => 0,
                            'profit' => 0,
                        ];
                    }
                    $productProfits[$productId]['quantity_sold'] += $item->quantity;
                    $productProfits[$productId]['revenue'] += $item->subtotal;
                    $productProfits[$productId]['cost'] += $item->quantity * $item->product->purchase_price;
                    $productProfits[$productId]['profit'] = $productProfits[$productId]['revenue'] - $productProfits[$productId]['cost'];
                }
            }

            usort($productProfits, function($a, $b) {
                return $b['profit'] <=> $a['profit'];
            });

            $dateRange = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));

            $data = [
                'title' => 'Laporan Keuntungan',
                'date' => date('d F Y'),
                'date_range' => $dateRange,
                'total_sales' => $totalSales,
                'total_purchases' => $totalPurchases,
                'gross_profit' => $grossProfit,
                'profit_margin' => $profitMargin,
                'product_profits' => $productProfits,
            ];

            $pdf = Pdf::loadView('reports.pdf.profit', $data);

            $filename = 'laporan-keuntungan-' . date('Y-m-d-His') . '.pdf';
            $path = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $pdf->save($path);

            $reportData = [
                'title' => 'Laporan Keuntungan',
                'type_label' => 'Laporan Profit/Keuntungan',
                'owner_name' => Setting::get('owner_name', 'Owner'),
                'date_range' => $dateRange,
                'total_items' => count($productProfits) . ' produk',
                'grand_total' => 'Rp ' . number_format($grossProfit, 0, ',', '.') . ' (' . number_format($profitMargin, 2) . '%)',
            ];

            Mail::to($request->owner_email)
                ->send(new ReportMail('profit', $reportData, $path, $dateRange));

            if (file_exists($path)) {
                unlink($path);
            }

            return redirect()->back()->with('success', 'Laporan keuntungan berhasil dikirim ke ' . $request->owner_email);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
