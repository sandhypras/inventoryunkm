<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #f97316; }
        .header h1 { margin: 0; color: #f97316; font-size: 20pt; }
        .header p { margin: 5px 0; color: #666; }
        .info-box { background: #f8f9fa; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .info-box table { width: 100%; }
        .info-box td { padding: 3px 5px; }
        .info-box .label { font-weight: bold; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f97316; color: white; padding: 8px 5px; text-align: left; font-size: 9pt; border: 1px solid #f97316; }
        td { padding: 6px 5px; border: 1px solid #ddd; font-size: 9pt; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background: #e9ecef !important; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 8pt; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>{{ config('app.name', 'Inventory UMKM') }}</p>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td class="label">Periode:</td>
                <td>{{ $date_range }}</td>
                <td class="label">Total Transaksi:</td>
                <td>{{ count($transactions) }} transaksi</td>
            </tr>
            <tr>
                <td class="label">Total Pembelian:</td>
                <td colspan="3"><strong>Rp {{ number_format($total_purchases, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode</th>
                <th width="12%">Tanggal</th>
                <th width="25%">Supplier</th>
                <th width="10%" class="text-center">Items</th>
                <th width="16%" class="text-right">Subtotal</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($transactions as $trans)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $trans->code }}</td>
                <td>{{ $trans->date->format('d M Y') }}</td>
                <td>{{ $trans->supplier->name }}</td>
                <td class="text-center">{{ $trans->items->count() }}</td>
                <td class="text-right">{{ number_format($trans->items->sum('subtotal'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($trans->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse

            @if(count($transactions) > 0)
            <tr class="total-row">
                <td colspan="6" class="text-right">GRAND TOTAL:</td>
                <td class="text-right">Rp {{ number_format($total_purchases, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d F Y H:i') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
