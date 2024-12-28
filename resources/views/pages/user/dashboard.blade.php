@extends('layouts.user')

@section('content')
<div class="container-fluid">
    <main class="px-md-4">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 text-success"> Dashboard Pengguna</h1>
        </div>

        <!-- Kartu Total Emisi -->
        <div class="card bg-dark text-white mb-4 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-globe-americas fa-3x mb-3"></i>
                <h5 class="card-title">Total Emisi Carbon (Approved)</h5>
                <p class="card-text display-6">
                    {{ number_format($totalEmisiApprovedTon, 3) }}
                    <small class="fs-6">ton CO₂e</small>
                </p>
                <p class="card-text">
                    <small>
                        Konversi dari {{ number_format($totalEmisiApprovedTon * 1000, 2) }} kg CO₂e
                    </small>
                </p>
            </div>
        </div>
         <!-- Kategori Emisi -->
         <div class="row mb-4">
            @foreach($emisiPerSubKategori as $kategori => $subKategoriList)
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white d-flex align-items-center">
                        <h5 class="mb-0">
                            @switch($kategori)
                                @case('transportasi') <i class="fas fa-car me-2"></i> @break
                                @case('listrik') <i class="fas fa-lightbulb me-2"></i> @break
                                @case('sampah') <i class="fas fa-trash me-2"></i> @break
                                @case('air') <i class="fas fa-tint me-2"></i> @break
                                @case('gas') <i class="fas fa-fire me-2"></i> @break
                                @default <i class="fas fa-globe me-2"></i>
                            @endswitch
                            {{ ucfirst($kategori) }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($subKategoriList as $subKategori)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 border-success">
                                    <div class="card-body">
                                        <h6 class="card-title text-success">
                                            {{ ucfirst($subKategori['sub_kategori']) }}
                                            <span class="badge bg-success float-end">{{ $subKategori['jumlah_pengajuan'] }} pengajuan</span>
                                        </h6>
                                        <p class="card-text">
                                            <span class="h4">{{ number_format($subKategori['total_emisi'], 2) }}</span>
                                            <small>kg CO₂e</small>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Update: {{ \Carbon\Carbon::parse($subKategori['last_update'])->format('d/m/Y') }}
                                            </small>
                                        </p>
                                        <a href="{{ route('emisicarbon.index') }}" class="btn btn-outline-success btn-sm">
                                            Detail <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Grafik -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Grafik Emisi Carbon Bulanan (Approved)</h5>
            </div>
            <div class="card-body">
                @if(isset($chartData['labels']) && isset($chartData['data']))
                    <canvas id="emissionChart"></canvas>
                @else
                    <p class="text-center text-muted">Data grafik tidak tersedia</p>
                @endif
            </div>
        </div>
    </main>
</div>

@push('scripts')
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
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
.bg-success {
    background: linear-gradient(45deg, #28a745, #218838) !important;
}
.card-header {
    font-size: 1.25rem;
    font-weight: 600;
}
.display-6 {
    font-size: 1.75rem;
    font-weight: bold;
}
</style>
@endpush
@endsection
