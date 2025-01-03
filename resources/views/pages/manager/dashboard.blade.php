@extends('layouts.manager')

@section('content')
<div class="container-fluid">
    <main class="px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 text-success">Dashboard Manager</h1>
        </div>

        <!-- Kartu Informasi Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5 class="card-title">Total Pengguna</h5>
                        <p class="card-text display-6">
                            {{ $totalPengguna }}
                            <small class="fs-6">pengguna</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-cloud fa-3x mb-3"></i>
                        <h5 class="card-title">Total Emisi Approve</h5>
                        <p class="card-text display-6">
                            {{ number_format($totalEmisiPerTahun / 1000, 3) }}
                            <small class="fs-6">ton CO<sub>2</sub></small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-cloud fa-3x mb-3"></i>
                        <h5 class="card-title">Total Emisi Pending</h5>
                        <p class="card-text display-6">
                            {{ number_format($totalEmisiPerTahunPending / 1000, 3) }}
                            <small class="fs-6">ton CO<sub>2</sub></small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-hourglass-half fa-3x mb-3"></i>
                        <h5 class="card-title">Emisi Pending</h5>
                        <p class="card-text display-6">
                            {{ $totalEmisiPending }}
                            <small class="fs-6">pengajuan</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-dark text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-globe-americas fa-3x mb-3"></i>
                        <h5 class="card-title">Total Emisi Carbon (Approved)</h5>
                        <p class="card-text display-6">
                            {{ number_format($totalEmisiApprovedTon, 3) }}
                            <small class="fs-6">ton CO<sub>2</sub>e</small>
                        </p>
                        <p class="card-text">
                            <small>
                                Konversi dari {{ number_format($totalEmisiApprovedTon * 1000, 2) }} kg CO₂
                            </small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-purple text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-leaf fa-3x mb-3"></i>
                        <h5 class="card-title">Total Terkompensasi</h5>
                        <p class="card-text display-6">
                            {{ number_format($totalKompensasi, 3) }}
                            <small class="fs-6">ton CO<sub>2</sub>e</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-teal text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-percentage fa-3x mb-3"></i>
                        <h5 class="card-title">Persentase Kompensasi</h5>
                        <p class="card-text display-6">
                            {{ number_format($persentaseKompensasi, 1) }}
                            <small class="fs-6">%</small>
                        </p>
                        <small>dari total emisi approved</small>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Grafik -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Grafik Emisi Carbon Bulanan (Approved)</h5>
            </div>
            <div class="card-body">
                @if(!empty($chartData['labels']) && !empty($chartData['data']) && count($chartData['labels']) > 0)
                <canvas id="emissionChart" height="400" width="1000"></canvas>
                @else
                    <div class="alert alert-info">
                        Belum ada data emisi yang disetujui untuk ditampilkan dalam grafik.
                    </div>
                @endif
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
                    label: 'Total Emisi Carbon Approved (kg)',
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
                        text: 'Total Emisi Carbon yang Disetujui per Bulan'
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
    transform: scale(1.02);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.list-group-item {
    border: none;
    padding: 1rem;
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}
.list-group-item:hover {
    background-color: #e9ecef;
}
.bg-purple {
    background-color: #8e44ad !important;
}
.bg-teal {
    background-color: #16a085 !important;
}
.card .card-text small {
    font-size: 0.875rem;
    opacity: 0.8;
}
</style>
@endpush
@endsection
