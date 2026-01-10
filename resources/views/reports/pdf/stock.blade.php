<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
            font-size: 20pt;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 5px;
        }
        .info-box .label {
            font-weight: bold;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #667eea;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 9pt;
            border: 1px solid #667eea;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background: #e9ecef !important;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            display: inline-block;
        }
        .status-low {
            background: #fee;
            color: #c00;
        }
        .status-ok {
            background: #efe;
            color: #090;
        }
        .status-out {
            background: #fdd;
            color: #900;
        }
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
                <td class="label">Total Item Produk:</td>
                <td>{{ $total_items }} item</td>
                <td class="label">Total Nilai Stok:</td>
                <td>Rp {{ number_format($total_value, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Kode</th>
                <th width="25%">Nama Produk</th>
                <th width="15%">Kategori</th>
                <th width="8%">Stok</th>
                <th width="8%">Min</th>
                <th width="5%">Satuan</th>
                <th width="12%" class="text-right">Harga Modal</th>
                <th width="10%" class="text-right">Nilai Stok</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($products as $product)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td class="text-center">
                    {{ $product->stock }}
                    @if($product->stock == 0)
                        <span class="status-badge status-out">Habis</span>
                    @elseif($product->stock <= $product->min_stock)
                        <span class="status-badge status-low">Rendah</span>
                    @endif
                </td>
                <td class="text-center">{{ $product->min_stock }}</td>
                <td>{{ $product->unit }}</td>
                <td class="text-right">{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse

            @if($products->count() > 0)
            <tr class="total-row">
                <td colspan="8" class="text-right">TOTAL NILAI STOK:</td>
                <td class="text-right">Rp {{ number_format($total_value, 0, ',', '.') }}</td>
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
