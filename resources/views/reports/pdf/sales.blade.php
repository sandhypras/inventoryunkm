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
                <td>{{ $stockOuts->count() }} transaksi</td>
                <td class="label">Total Penjualan:</td>
                <td><strong>Rp {{ number_format($totalSales, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Total Modal:</td>
                <td>Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td class="label">Total Profit:</td>
                <td><strong style="color: green;">Rp {{ number_format($totalProfit, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Margin Keuntungan:</td>
                <td colspan="3"><strong>{{ $totalModal > 0 ? number_format(($totalProfit / $totalModal) * 100, 1) : 0 }}%</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 18%;">Kode Transaksi</th>
                <th style="width: 20%;">Customer</th>
                <th style="width: 10%;" class="text-center">Items</th>
                <th style="width: 15%;" class="text-right">Modal</th>
                <th style="width: 20%;" class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalModalCalc = 0; @endphp
            @foreach($stockOuts as $index => $stockOut)
                @php
                    $modal = 0;
                    foreach($stockOut->items as $item) {
                        $modal += $item->quantity * $item->product->purchase_price;
                    }
                    $totalModalCalc += $modal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d M Y', strtotime($stockOut->date)) }}</td>
                    <td>{{ $stockOut->code }}</td>
                    <td>{{ $stockOut->customer_name ?: 'Umum' }}</td>
                    <td class="text-center">{{ $stockOut->items->count() }} item</td>
                    <td class="text-right">Rp {{ number_format($modal, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($stockOut->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($totalModalCalc, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: #e8f5e9;">
                <td colspan="6" class="text-right" style="color: green;"><strong>TOTAL PROFIT:</strong></td>
                <td class="text-right" style="color: green;"><strong>Rp {{ number_format($totalProfit, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ date('d F Y H:i:s') }} | Sistem Inventory UMKM</p>
    </div>
</body>
</html>
