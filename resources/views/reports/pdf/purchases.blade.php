<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #666; }
        .summary { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .summary table { width: 100%; }
        .summary td { padding: 5px; font-size: 11px; }
        .summary .label { font-weight: bold; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #333; color: white; padding: 8px; text-align: left; font-size: 11px; }
        td { padding: 6px; border-bottom: 1px solid #ddd; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        tfoot { font-weight: bold; background: #f5f5f5; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Periode: {{ $dateRange }}</p>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td class="label">Total Transaksi:</td>
                <td>{{ $stockIns->count() }} transaksi</td>
                <td class="label">Total Pembelian:</td>
                <td><strong>Rp {{ number_format($totalPurchases, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Rata-rata/Transaksi:</td>
                <td colspan="3">Rp {{ number_format($stockIns->count() > 0 ? $totalPurchases / $stockIns->count() : 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 20%;">Kode Transaksi</th>
                <th style="width: 28%;">Supplier</th>
                <th style="width: 10%;" class="text-center">Items</th>
                <th style="width: 25%;" class="text-right">Total Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockIns as $index => $stockIn)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d M Y', strtotime($stockIn->date)) }}</td>
                    <td>{{ $stockIn->code }}</td>
                    <td>{{ $stockIn->supplier->name }}</td>
                    <td class="text-center">{{ $stockIn->items->count() }} item</td>
                    <td class="text-right">Rp {{ number_format($stockIn->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL PEMBELIAN:</td>
                <td class="text-right">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Detail Items per Transaksi -->
    <div style="margin-top: 30px;">
        <h3 style="margin-bottom: 10px; font-size: 14px;">Detail Item Pembelian</h3>
        @foreach($stockIns as $stockIn)
            <div style="margin-bottom: 20px; page-break-inside: avoid;">
                <div style="background: #e3f2fd; padding: 8px; margin-bottom: 5px; border-radius: 3px;">
                    <strong>{{ $stockIn->code }}</strong> - {{ $stockIn->supplier->name }}
                    ({{ date('d M Y', strtotime($stockIn->date)) }})
                </div>
                <table style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 45%;">Produk</th>
                            <th style="width: 15%;" class="text-right">Qty</th>
                            <th style="width: 17%;" class="text-right">Harga</th>
                            <th style="width: 18%;" class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockIn->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-right">{{ $item->quantity }} {{ $item->product->unit }}</td>
                                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right"><strong>Rp {{ number_format($stockIn->total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>Dicetak pada {{ date('d F Y H:i:s') }} | Sistem Inventory UMKM</p>
    </div>
</body>
</html>
