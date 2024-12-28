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
        .sub-category {
            padding-left: 20px;
            font-size: 11px;
            background-color: #fafafa;
        }
        .stats-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .highlight {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        <!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        /* ... existing styles ... */
        .detail-row { background-color: #f9f9f9; }
        .status-approved { color: green; }
        .status-pending { color: orange; }
        .status-rejected { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ $date }}</p>
        <p>Admin: {{ $admin }}</p>
    </div>

    <div class="summary">
        <h2>Ringkasan Emisi Karbon</h2>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Pengajuan</th>
                    <th>Total Emisi (kg CO₂)</th>
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
                    <th>Kadar Emisi (kg CO₂)</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emisi_carbons as $emisi)
                    <tr class="detail-row">
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

    <div class="footer">
        <p>Dicetak oleh: {{ $admin }}</p>
        <p>Tanggal: {{ $date }}</p>
    </div>
</body>
</html>