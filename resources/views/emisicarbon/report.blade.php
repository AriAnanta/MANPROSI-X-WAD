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
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid #2c3e50;
            background: #f8f9fa;
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
        .summary, .details {
            margin-bottom: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .summary h2, .details h2 {
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
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #2c3e50;
            color: white;
            background-color: #2c3e50;
            color: white;
        }
        td {
            word-wrap: break-word; /* Membungkus kata panjang */
            white-space: pre-wrap; /* Membungkus teks dengan spasi */
        }
        .col-kode {
            width: 15%;
        }
        .col-tanggal {
            width: 12%;
        }
        .col-pengguna {
            width: 15%;
        }
        .col-kategori {
            width: 12%;
        }
        .col-kadar {
            width: 12%;
            text-align: right;
        }
        .col-status {
            width: 12%;
            text-align: center;
        }
        .col-deskripsi {
            width: auto; /* Lebar otomatis untuk teks panjang */
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .highlight {
            background-color: #e8f4f8 !important;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 50px;
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
    <div class="content-wrapper">

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
                        <th class="col-kode">Kode Emisi</th>
                        <th class="col-tanggal">Tanggal</th>
                        <th class="col-pengguna">Pengguna</th>
                        <th class="col-kategori">Kategori</th>
                        <th class="col-kadar">Kadar Emisi (kg CO2e)</th>
                        <th class="col-status">Status</th>
                        <th class="col-deskripsi">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emisi_carbons as $emisi)
                        <tr>
                            <td class="col-kode">{{ $emisi->kode_emisi_karbon }}</td>
                            <td class="col-tanggal">{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                            <td class="col-pengguna">{{ $emisi->nama_user }}</td>
                            <td class="col-kategori">{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                            <td class="col-kadar">{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                            <td class="col-status">{{ ucfirst($emisi->status) }}</td>
                            <td class="col-deskripsi">{{ $emisi->deskripsi }}</td>
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
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Carbon Footprint</p>
    </div>
</body>
</html>
