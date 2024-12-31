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
        .meta-info {
            margin-bottom: 20px;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 5px;
        }
        .summary {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .summary h2 {
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
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
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .highlight {
            background-color: #e8f4f8 !important;
            font-weight: bold;
        }
        .status-approved {
            color: #27ae60;
            font-weight: bold;
        }
        .status-pending {
            color: #f39c12;
            font-weight: bold;
        }
        .status-rejected {
            color: #c0392b;
            font-weight: bold;
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
        .clear {
            clear: both;
        }
        .content-wrapper {
            padding-bottom: 100px;
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
            <p><strong>Admin yang Mencetak:</strong> {{ $admin }}</p>
            <p><strong>Nomor Dokumen:</strong> DOC-{{ date('Ymd-His') }}</p>
        </div>

        <div class="summary">
            <h2>Ringkasan Emisi Karbon</h2>
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Pengajuan</th>
                        <th>Total Emisi (kg CO2e)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emisi_per_kategori as $kategori => $data)
                        <tr>
                            <td>{{ ucfirst($kategori) }}</td>
                            <td>{{ $data['jumlah_pengajuan'] }}</td>
                            <td>{{ number_format($data['total_emisi'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="highlight">
                        <td><strong>Total</strong></td>
                        <td><strong>{{ $total_pengajuan }}</strong></td>
                        <td><strong>{{ number_format($total_emisi, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="details">
            <h2>Detail Emisi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Kode Emisi</th>
                        <th>Tanggal</th>
                        <th>Pengguna</th>
                        <th>Kategori</th>
                        <th>Kadar Emisi (kg CO2e)</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emisi_carbons as $emisi)
                        <tr>
                            <td>{{ $emisi->kode_emisi_karbon }}</td>
                            <td>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                            <td>{{ $emisi->nama_user }}</td>
                            <td>{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                            <td>{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                            <td class="status-{{ strtolower($emisi->status) }}">
                                {{ ucfirst($emisi->status) }}
                            </td>
                            <td>{{ $emisi->deskripsi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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