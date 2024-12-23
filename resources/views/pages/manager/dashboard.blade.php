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
                            {{ number_format($totalEmisiPerTahun, 2) }}
                            <small class="fs-6">kg CO<sub>2</sub></small>
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
                            {{ number_format($totalEmisiPerTahunPending, 2) }}
                            <small class="fs-6">kg CO<sub>2</sub></small>
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
        </div>
        

        <div class="row">
            <!-- Grafik Emisi Bulanan -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Grafik Emisi Karbon Bulanan</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($chartData['labels']) && isset($chartData['data']))
                        <canvas id="emissionChart" height="400" width="800"></canvas>
                        @else
                            <p class="text-center text-muted">Data grafik tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Emisi Pending Terbaru -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Pengajuan Emisi Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($emisiPending as $emisi)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 text-success">{{ $emisi->nama_user }}</h6>
                                    <small>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</small>
                                </div>
                                <p class="mb-1 text-muted">{{ ucfirst($emisi->kategori_emisi_karbon) }}</p>
                                <p class="mb-1">{{ number_format($emisi->kadar_emisi_karbon, 2) }} kg CO<sub>2</sub></p>
                                <div class="mt-2 text-center">
                                    <button class="btn btn-sm btn-success me-2">Setujui</button>
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                </div>
                            </div>
                            @empty
                            <p class="text-center">Tidak ada pengajuan emisi pending</p>
                            @endforelse
                     </div>
                    </div>
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
                    label: 'Total Emisi Karbon (kg CO₂)',
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
                        text: 'Total Emisi Karbon per Bulan'
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
</style>
@endpush
@endsection
