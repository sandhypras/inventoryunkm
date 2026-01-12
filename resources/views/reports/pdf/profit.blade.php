<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #8b5cf6; }
        .header h1 { margin: 0; color: #8b5cf6; font-size: 20pt; }
        .header p { margin: 5px 0; color: #666; }
        .summary-box { background: #f8f9fa; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .summary-grid { display: table; width: 100%; }
        .summary-item { display: table-cell; padding: 10px; text-align: center; border-right: 1px solid #ddd; }
        .summary-item:last-child { border-right: none; }
        .summary-label { font-size: 8pt; color: #666; margin-bottom: 5px; }
        .summary-value { font-size: 14pt; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #8b5cf6; color: white; padding: 8px 5px; text-align: left; font-size: 9pt; border: 1px solid #8b5cf6; }
        td { padding: 6px 5px; border: 1px solid #ddd; font-size: 9pt; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background: #e9ecef !important; }
        .profit-positive { color: #10b981; font-weight: bold; }
        .profit-negative { color: #ef4444; font-weight: bold; }
        .margin-badge { padding: 2px 6px; border-radius: 3px; font-size: 8pt; }
        .margin-high { background: #d1fae5; color: #065f46; }
        .margin-medium { background: #fef3c7; color: #92400e; }
        .margin-low { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 8pt; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>{{ config('app.name', 'Inventory UMKM') }}</p>
        <p>Tanggal Cetak: {{ $date }} | Periode: {{ $date_range }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Penjualan</div>
                <div class="summary-value" style="color: #10b981;">Rp {{ number_format($total_sales, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pembelian</div>
                <div class="summary-value" style="color: #f97316;">Rp {{ number_format($total_purchases, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Keuntungan Bersih</div>
                <div class="summary-value" style="color: #3b82f6;">Rp {{ number_format($gross_profit, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Margin</div>
                <div class="summary-value" style="color: #8b5cf6;">{{ number_format($profit_margin, 2) }}%</div>
            </div>
        </div>
    </div>

    <h3 style="margin-top: 20px; color: #374151;">Keuntungan per Produk</h3>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Produk</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="17%" class="text-right">Revenue</th>
                <th width="17%" class="text-right">Modal</th>
                <th width="17%" class="text-right">Profit</th>
                <th width="9%" class="text-center">Margin</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($product_profits as $item)
            @php
                $margin = $item['revenue'] > 0 ? ($item['profit'] / $item['revenue']) * 100 : 0;
                $marginClass = $margin > 30 ? 'margin-high' : ($margin > 15 ? 'margin-medium' : 'margin-low');
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>
                    <strong>{{ $item['product']->name }}</strong><br>
                    <small style="color: #6b7280;">{{ $item['product']->category->name ?? '-' }}</small>
                </td>
                <td class="text-center">{{ $item['quantity_sold'] }}</td>
                <td class="text-right">{{ number_format($item['revenue'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item['cost'], 0, ',', '.') }}</td>
                <td class="text-right {{ $item['profit'] > 0 ? 'profit-positive' : 'profit-negative' }}">
                    {{ number_format($item['profit'], 0, ',', '.') }}
                </td>
                <td class="text-center">
                    <span class="margin-badge {{ $marginClass }}">{{ number_format($margin, 1) }}%</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data penjualan dalam periode ini</td>
            </tr>
            @endforelse

            @if(count($product_profits) > 0)
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">Rp {{ number_format($total_sales, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($total_purchases, 0, ',', '.') }}</td>
                <td class="text-right profit-positive">Rp {{ number_format($gross_profit, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="margin-badge {{ $profit_margin > 30 ? 'margin-high' : ($profit_margin > 15 ? 'margin-medium' : 'margin-low') }}">
                        {{ number_format($profit_margin, 1) }}%
                    </span>
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <div style="margin-top: 20px; padding: 10px; background: #eff6ff; border-left: 4px solid #3b82f6;">
        <p style="margin: 0; font-size: 9pt; color: #1e40af;">
            <strong>💡 Catatan:</strong>
            Margin Keuntungan = (Profit / Revenue) × 100%.
            Margin > 30% = Sangat Baik, 15-30% = Cukup Baik, < 15% = Perlu Ditingkatkan.
        </p>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ date('d F Y H:i') }}</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
