<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $query = StockIn::with(['supplier', 'user', 'items']);

        // Filter by search
        if ($request->search) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        // Filter by supplier
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockIns = $query->latest('date')->paginate(15);
        $suppliers = Supplier::orderBy('name')->get();

        return view('stock-ins.index', compact('stockIns', 'suppliers'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'purchase_price', 'unit']);

        $suppliers = Supplier::orderBy('name')->get();

        // Generate preview code
        $lastStockIn = StockIn::whereDate('created_at', today())->latest()->first();
        $number = $lastStockIn ? intval(substr($lastStockIn->code, -4)) + 1 : 1;
        $code = 'SI-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return view('stock-ins.create', compact('products', 'suppliers', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate code
            $lastStockIn = StockIn::whereDate('created_at', today())->latest()->first();
            $number = $lastStockIn ? intval(substr($lastStockIn->code, -4)) + 1 : 1;
            $code = 'SI-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            // Calculate total
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            // Create stock in
            $stockIn = StockIn::create([
                'code' => $code,
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total' => $total,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            // Create stock in items and update product stock
            foreach ($request->items as $item) {
                StockInItem::create([
                    'stock_in_id' => $stockIn->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->increment('stock', $item['quantity']);

                // Update purchase price if different
                if ($product->purchase_price != $item['price']) {
                    $product->update(['purchase_price' => $item['price']]);
                }
            }

            DB::commit();

            return redirect()->route('stock-ins.index')
                ->with('success', 'Transaksi barang masuk berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(StockIn $stockIn)
    {
        $stockIn->load(['supplier', 'user', 'items.product']);

        return view('stock-ins.show', compact('stockIn'));
    }

    public function destroy(StockIn $stockIn)
    {
        DB::beginTransaction();
        try {
            // Restore product stock
            foreach ($stockIn->items as $item) {
                $product = Product::find($item->product_id);
                $product->decrement('stock', $item->quantity);
            }

            // Delete stock in (items will be deleted by cascade)
            $stockIn->delete();

            DB::commit();

            return redirect()->route('stock-ins.index')
                ->with('success', 'Transaksi barang masuk berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
