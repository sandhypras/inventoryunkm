<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\StockOutItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with(['user', 'items']);

        // Filter by search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $stockOuts = $query->latest('date')->paginate(15);

        return view('stock-outs.index', compact('stockOuts'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'code', 'name', 'selling_price', 'stock', 'unit']);

        // Generate preview code
        $lastStockOut = StockOut::whereDate('created_at', today())->latest()->first();
        $number = $lastStockOut ? intval(substr($lastStockOut->code, -4)) + 1 : 1;
        $code = 'SO-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return view('stock-outs.create', compact('products', 'code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Validate stock availability
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi! Tersedia: {$product->stock}");
                }
            }

            // Generate code
            $lastStockOut = StockOut::whereDate('created_at', today())->latest()->first();
            $number = $lastStockOut ? intval(substr($lastStockOut->code, -4)) + 1 : 1;
            $code = 'SO-' . date('Ymd') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            // Calculate total
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['price'];
            }

            // Create stock out
            $stockOut = StockOut::create([
                'code' => $code,
                'customer_name' => $request->customer_name,
                'date' => $request->date,
                'total' => $total,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            // Create stock out items and update product stock
            foreach ($request->items as $item) {
                StockOutItem::create([
                    'stock_out_id' => $stockOut->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('stock-outs.index')
                ->with('success', 'Transaksi penjualan berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(StockOut $stockOut)
    {
        $stockOut->load(['user', 'items.product']);

        return view('stock-outs.show', compact('stockOut'));
    }

    public function destroy(StockOut $stockOut)
    {
        DB::beginTransaction();
        try {
            // Restore product stock
            foreach ($stockOut->items as $item) {
                $product = Product::find($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            // Delete stock out (items will be deleted by cascade)
            $stockOut->delete();

            DB::commit();

            return redirect()->route('stock-outs.index')
                ->with('success', 'Transaksi penjualan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
