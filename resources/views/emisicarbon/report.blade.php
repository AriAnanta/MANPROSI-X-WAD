<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .meta-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary h2 {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="meta-info">
        <p>Admin: {{ $admin }}</p>
    </div>

    <!-- Ringkasan per Kategori -->
    <div class="summary">
        <h2>Ringkasan per Kategori</h2>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Pengajuan</th>
                    <th>Total Emisi (kg CO2)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emisi_per_kategori as $kategori)
                    <tr>
                        <td>{{ ucfirst($kategori->kategori_emisi_karbon) }}</td>
                        <td style="text-align: center">{{ $kategori->jumlah_pengajuan }}</td>
                        <td style="text-align: right">{{ number_format($kategori->total_emisi, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Detail Emisi -->
    <h2>Detail Emisi Karbon</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pengguna</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Kadar Emisi (kg CO2)</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emisi_carbons as $index => $emisi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $emisi->nama_user }}</td>
                    <td>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                    <td>{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                    <td style="text-align: right">{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                    <td>{{ $emisi->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Emisi Karbon: {{ number_format($total_emisi, 2) }} kg CO2</p>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>
</body>
</html> 