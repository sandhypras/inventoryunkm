<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #6c757d;
        }
        .value {
            color: #495057;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 {{ $reportData['title'] ?? 'Laporan' }}</h1>
        <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">
            {{ config('app.name', 'Inventory UMKM') }}
        </p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $reportData['owner_name'] ?? 'Owner' }}</strong>,</p>

        <p>Berikut adalah laporan yang Anda minta:</p>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Jenis Laporan:</span>
                <span class="value">{{ $reportData['type_label'] ?? 'Laporan' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal Dibuat:</span>
                <span class="value">{{ date('d F Y H:i') }}</span>
            </div>
            @if(isset($reportData['date_range']))
            <div class="info-row">
                <span class="label">Periode:</span>
                <span class="value">{{ $reportData['date_range'] }}</span>
            </div>
            @endif
            @if(isset($reportData['total_items']))
            <div class="info-row">
                <span class="label">Total Item:</span>
                <span class="value">{{ $reportData['total_items'] }}</span>
            </div>
            @endif
            @if(isset($reportData['grand_total']))
            <div class="info-row">
                <span class="label">Grand Total:</span>
                <span class="value">{{ $reportData['grand_total'] }}</span>
            </div>
            @endif
        </div>

        <p>
            Laporan lengkap terlampir dalam format PDF. Silakan buka attachment untuk melihat detail lengkap.
        </p>

        <p>
            <strong>💡 Tips:</strong> Simpan file PDF ini untuk arsip dan referensi di masa mendatang.
        </p>

        <div class="footer">
            <p>
                Email ini dikirim otomatis oleh sistem.<br>
                Jika ada pertanyaan, silakan hubungi administrator.
            </p>
            <p style="margin-top: 10px; font-size: 12px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
