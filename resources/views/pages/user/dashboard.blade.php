<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna - GreenLedger</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Navbar -->
                @include('layouts.navbar')

                <!-- Content -->
                <div class="container mt-4">
                    <h2 class="mb-4">Dashboard Pengguna</h2>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Emisi Karbon Anda</h5>
                                    <p class="card-text display-6">{{ number_format($totalEmisi, 2) }} kg CO<sub>2</sub></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Carbon Credit Anda</h5>
                                    <p class="card-text display-6">{{ $carbonCredits }} Credits</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Status Emisi</h5>
                                    <p class="card-text display-6">{{ $statusEmisi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Emisi -->
                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Riwayat Emisi Karbon
                                </div>
                                <div class="card-body">
                                    <canvas id="emissionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    Aksi Cepat
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('emisicarbon.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus-circle"></i> Input Emisi Baru
                                        </a>
                                        <button class="btn btn-outline-success" onclick="alert('Fitur akan segera hadir!')">
                                            <i class="fas fa-shopping-cart"></i> Beli Carbon Credit
                                        </button>
                                        <button class="btn btn-outline-primary" onclick="alert('Fitur akan segera hadir!')">
                                            <i class="fas fa-download"></i> Unduh Laporan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Aktivitas Terakhir -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    Aktivitas Terakhir
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Aktivitas</th>
                                                    <th>Emisi (kg CO2)</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentActivities as $activity)
                                                <tr>
                                                    <td>{{ $activity->created_at->format('d M Y') }}</td>
                                                    <td>{{ $activity->description }}</td>
                                                    <td>{{ number_format($activity->emission_amount, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $activity->status_color }}">
                                                            {{ $activity->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart Script -->
    <script>
        const ctx = document.getElementById('emissionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Emisi Karbon (kg CO2)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html> 