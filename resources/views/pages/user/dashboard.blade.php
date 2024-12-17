@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <!-- Main Content -->
    <main class="px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard Pengguna</h1>
        </div>

        <!-- Kartu Informasi per Kategori -->
        <div class="row mb-4">
            @foreach($emisiPerKategori as $kategori)
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <!-- Ikon sesuai kategori -->
                        @if($kategori->kategori_emisi_karbon === 'transportasi')
                        <i class="fas fa-car fa-3x mb-3"></i>
                        @elseif($kategori->kategori_emisi_karbon === 'energi')
                        <i class="fas fa-bolt fa-3x mb-3"></i>
                        @elseif($kategori->kategori_emisi_karbon === 'Sampah')
                        <i class="fas fa-recycle fa-3x mb-3"></i> <!-- Ikon baru -->
                        @else
                        <i class="fas fa-leaf fa-3x mb-3"></i>
                        @endif
                        <h5 class="card-title">{{ ucfirst($kategori->kategori_emisi_karbon) }}</h5>
                        <p class="card-text display-6">
                            {{ number_format($kategori->total_emisi, 2) }}
                            <small class="fs-6">kg CO<sub>2</sub></small>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        

        <div class="row">
            <!-- Grafik Emisi Bulanan -->
            <div class="col-md-8 mt-4">
                <div class="card border-0 shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Grafik Emisi Karbon Bulanan</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($chartData['labels']) && isset($chartData['data']))
                            <div style="max-width: 100%; height: 300px; margin: auto;">
                                <canvas id="emissionChart"></canvas>
                            </div>
                        @else
                            <p class="text-center text-muted">Data grafik tidak tersedia</p>
                        @endif
                    </div>
                </div>
            </div>
            

            <!-- Daftar Emisi Pending -->
            <div class="col-md-4 mt-4">
                <div class="card border-0 shadow">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Emisi Pending</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($emisiPending as $emisi)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ ucfirst($emisi->kategori_emisi_karbon) }}</h6>
                                    <small class="text-muted">{{ $emisi->tanggal_emisi->format('d/m/Y') }}</small>
                                    <p class="mb-1">{{ number_format($emisi->kadar_emisi_karbon, 2) }} kg CO<sub>2</sub></p>
                                </div>
                                <i class="bi bi-hourglass-split text-warning"></i>
                            </div>
                            @empty
                            <div class="list-group-item text-center">
                                <p class="mb-0 text-muted">Tidak ada emisi pending</p>
                            </div>
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
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
.list-group-item {
    background-color: #f9f9f9;
    border-radius: 5px;
}
</style>
@endpush
@endsection
