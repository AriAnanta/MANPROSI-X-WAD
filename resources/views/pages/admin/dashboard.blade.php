@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Statistik -->
    <div class="row g-3">
        <!-- Total Pengguna -->
        <div class="col-sm-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3">
                        <i class="bi bi-people-fill fs-2 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Total Pengguna</h6>
                        <h5 class="card-title mb-0">{{ $totalUsers }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Emisi Carbon -->
        <div class="col-sm-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3">
                        <i class="bi bi-cloud-fill fs-2 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Total Emisi Carbon</h6>
                        <h5 class="card-title mb-0">{{ $totalEmissions }} kg</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Emisi per Pengguna -->
        <div class="col-sm-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3">
                        <i class="bi bi-graph-up fs-2 text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="card-subtitle mb-1 text-muted">Rata-rata Emisi/Pengguna</h6>
                        <h5 class="card-title mb-0">{{ $averageEmissionPerUser }} kg</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">Grafik Emisi Carbon Bulanan</h5>
            <canvas id="emissionChart" height="300"></canvas>
        </div>
    </div>

    <!-- Tabel Data Terbaru -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">Data Emisi Carbon Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Pengguna</th>
                            <th>Tanggal</th>
                            <th>Total Emisi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentEmissions as $emission)
                            <tr>
                                <td>{{ $emission->pengguna->nama_pengguna }}</td>
                                <td>{{ $emission->created_at->format('d M Y') }}</td>
                                <td>{{ $emission->kadar_emisi_karbon }} kg</td>
                                <td>
                                    <span class="badge bg-success">Terverifikasi</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('emissionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'Total Emisi Carbon (kg)',
                    data: {!! json_encode($chartData['data']) !!},
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
@endpush
