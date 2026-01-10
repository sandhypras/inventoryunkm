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
}
