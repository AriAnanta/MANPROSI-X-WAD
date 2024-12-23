<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Histori Notifikasi</title>
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
        <h1>Laporan Histori Notifikasi</h1>
        <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <div class="meta-info">
        <p>Admin: {{ Auth::guard('admin')->user()->nama_admin }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tujuan</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifikasi as $index => $notif)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $notif->pengguna->nama_user ?? $notif->kode_user }}</td>
                    <td>{{ $notif->kategori_notifikasi }}</td>
                    <td>{{ date('d/m/Y', strtotime($notif->tanggal)) }}</td>
                    <td>{{ $notif->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>
</body>
</html> 