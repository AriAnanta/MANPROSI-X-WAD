@extends('layouts.admin')

@section('title', 'Dashboard Admin - GreenLedger')

@section('content')
<div class="container-fluid">
    <main class="px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 text-success">Dashboard Admin</h1>
        </div>

        <!-- Kartu Informasi Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-light-green text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5 class="card-title">Total Pengguna</h5>
                        <p class="card-text display-6">
                            {{ $totalUsers }}
                            <small class="fs-6">pengguna</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-cloud fa-3x mb-3"></i>
                        <h5 class="card-title">Total Emisi Carbon</h5>
                        <p class="card-text display-6">
                            {{ number_format($totalEmissions, 2) }}
                            <small class="fs-6">kg CO<sub>2</sub></small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <h5 class="card-title">Rata-rata Emisi/Pengguna</h5>
                        <p class="card-text display-6">
                            {{ number_format($averageEmissionPerUser, 2) }}
                            <small class="fs-6">kg CO<sub>2</sub>/user</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Grafik Emisi Carbon Bulanan</h5>
            </div>
            <div class="card-body">
                <canvas id="emissionChart" height="400" width="800"></canvas>
            </div>
        </div>

        <!-- Tabel Data Terbaru -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-table"></i> Data Emisi Carbon Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user"></i> Pengguna</th>
                                <th><i class="fas fa-calendar-alt"></i> Tanggal</th>
                                <th><i class="fas fa-weight-hanging"></i> Total Emisi</th>
                                <th><i class="fas fa-check-circle"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentEmissions as $emission)
                                <tr>
                                    <td>{{ $emission->nama_user }}</td>
                                    <td>{{ date('d M Y', strtotime($emission->created_at)) }}</td>
                                    <td>{{ number_format($emission->kadar_emisi_karbon, 2) }} kg</td>
                                    <td>
                                        <span class="badge bg-{{ $emission->status === 'approved' ? 'success' : ($emission->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($emission->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('emissionChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Total Emisi Carbon (kg)',
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: 'rgba(46, 204, 113, 0.7)',
                borderColor: 'rgba(39, 174, 96, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Total Emisi Carbon per Bulan'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Emisi (kg CO₂)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });
</script>
@endpush

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 0.7rem 1.5rem rgba(0, 0, 0, 0.2);
    }
    .bg-light-green {
        background-color: #28a745;
        color: white;
    }
</style>
@endpush
@endsection
