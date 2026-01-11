<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { font-size: 20px; margin-bottom: 5px; }
        .header p { font-size: 11px; color: #666; }
        .summary { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .summary table { width: 100%; }
        .summary td { padding: 5px; font-size: 11px; }
        .summary .label { font-weight: bold; width: 120px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #333; color: white; padding: 6px; text-align: left; font-size: 10px; }
        td { padding: 5px; border-bottom: 1px solid #ddd; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        tfoot { font-weight: bold; background: #f5f5f5; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; text-align: center; font-size: 10px; color: #666; }
        .profit-positive { color: green; }
        .profit-negative { color: red; }
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
                <td class="label">Total Pendapatan:</td>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td class="label">Total Profit:</td>
                <td><strong class="profit-positive">Rp {{ number_format($totalProfit, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td class="label">Total Modal:</td>
                <td>Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                <td class="label">Rata-rata Margin:</td>
                <td><strong>{{ number_format($avgMargin, 1) }}%</strong></td>
            </tr>
            <tr>
                <td class="label">Total Transaksi:</td>
                <td colspan="3">{{ count($profitData) }} transaksi</td>
            </tr>
        </table>
    </div>

    <h3 style="margin-bottom: 10px; font-size: 14px;">Detail Profit per Transaksi</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 15%;">Kode</th>
                <th style="width: 15%;">Customer</th>
                <th style="width: 14%;" class="text-right">Pendapatan</th>
                <th style="width: 13%;" class="text-right">Modal</th>
                <th style="width: 14%;" class="text-right">Profit</th>
                <th style="width: 14%;" class="text-right">Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profitData as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ date('d/m/Y', strtotime($data['date'])) }}</td>
                    <td>{{ $data['code'] }}</td>
                    <td>{{ $data['customer'] }}</td>
                    <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($data['cost'], 0, ',', '.') }}</td>
                    <td class="text-right {{ $data['profit'] > 0 ? 'profit-positive' : 'profit-negative' }}">
                        Rp {{ number_format($data['profit'], 0, ',', '.') }}
                    </td>
                    <td class="text-right">{{ number_format($data['margin'], 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                <td class="text-right profit-positive">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($avgMargin, 1) }}%</td>
            </tr>
        </tfoot>
    </table>

    <!-- Summary Insights -->
    <div style="margin-top: 30px; background: #e8f5e9; padding: 15px; border-radius: 5px;">
        <h3 style="margin-bottom: 10px; font-size: 14px; color: green;">📊 Ringkasan Analisis</h3>
        <table style="width: 100%;">
            <tr>
                <td style="padding: 5px;"><strong>✓ Total Transaksi:</strong></td>
                <td style="padding: 5px;">{{ count($profitData) }} transaksi berhasil</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>✓ Margin Tertinggi:</strong></td>
                <td style="padding: 5px;">{{ count($profitData) > 0 ? number_format(max(array_column($profitData, 'margin')), 1) : 0 }}%</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>✓ Margin Terendah:</strong></td>
                <td style="padding: 5px;">{{ count($profitData) > 0 ? number_format(min(array_column($profitData, 'margin')), 1) : 0 }}%</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>✓ Profit per Transaksi:</strong></td>
                <td style="padding: 5px;">Rp {{ number_format(count($profitData) > 0 ? $totalProfit / count($profitData) : 0, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada {{ date('d F Y H:i:s') }} | Sistem Inventory UMKM</p>
        <p style="margin-top: 5px; font-style: italic;">Laporan ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>
</html>
