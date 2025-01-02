<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid #2c3e50;
            background: #f8f9fa;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 24px;
            color: #2c3e50;
            text-transform: uppercase;
        }
        .header p {
            color: #7f8c8d;
            margin: 5px 0;
        }
        .meta-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            word-wrap: break-word;
            max-width: 300px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin: 20px 0;
            padding: 15px;
            background: #e8f4f8;
            border-radius: 5px;
            font-size: 14px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 20px 0;
            border-top: 1px solid #eee;
            background: white;
        }
        .signature-section {
            margin-top: 50px;
            margin-bottom: 100px;
            page-break-inside: avoid;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
            margin-top: 30px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 10px;
            height: 40px;
        }
        .clear {
            clear: both;
        }
        .content-wrapper {
            padding-bottom: 100px;
        }
        table th:nth-child(1), table td:nth-child(1) { width: 20px; }  /* No */
        table th:nth-child(2), table td:nth-child(2) { width: 80px; } /* Kode Kompensasi */
        table th:nth-child(3), table td:nth-child(3) { width: 80px; } /* Kode Emisi */
        table th:nth-child(4), table td:nth-child(4) { width: 80px; } /* Kategori */
        table th:nth-child(5), table td:nth-child(5) { width: 60px; } /* Sub Kategori */
        table th:nth-child(6), table td:nth-child(6) { width: 60px; } /* Jumlah */
        table th:nth-child(7), table td:nth-child(7) { width: 60px; }  /* Tanggal */
        table th:nth-child(8), table td:nth-child(8) { width: 60px; }  /* Status */
        /* Style untuk teks panjang */
        .long-text {
            white-space: normal;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="header">
            <h1>{{ $title }}</h1>
            <p>Tanggal Cetak: {{ $date }}</p>
        </div>

        <div class="meta-info">
            <p><strong>Nomor Dokumen:</strong> DOC-{{ date('Ymd-His') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Kompensasi</th>
                    <th>Kode Emisi</th>
                    <th>Kategori</th>
                    <th>Sub Kategori</th>
                    <th>Jumlah (ton CO₂e)</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kompensasi as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->kode_kompensasi }}</td>
                        <td>{{ $item->kode_emisi_karbon }}</td>
                        <td>{{ ucfirst($item->kategori_emisi) }}</td>
                        <td>{{ ucfirst($item->sub_kategori) }}</td>
                        <td style="text-align: right">{{ number_format($item->jumlah_ton, 2) }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tanggal_kompensasi)) }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p>Total Kompensasi: {{ number_format($total_kompensasi, 2) }} ton CO2e</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Disetujui oleh:</p>
                <div class="signature-line"></div>
                <p><strong>Manager</strong></p>
                <p>{{ date('d F Y') }}</p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>
</body>
</html> 