@extends('layouts.app')

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
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
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

        <div class="row mb-4">
            <!-- Grafik Emisi per Tahun -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Total Emisi Karbon per Tahun</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="yearlyEmissionChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Daftar Emisi Pending -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Emisi Pending</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($emisiPending as $emisi)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ ucfirst($emisi->kategori_emisi_karbon) }}</h6>
                                    <small>{{ $emisi->tanggal_emisi->format('d/m/Y') }}</small>
                                </div>
                                <p class="mb-1">{{ number_format($emisi->kadar_emisi_karbon, 2) }} kg CO<sub>2</sub></p>
                                <small class="text-muted">{{ Str::limit($emisi->deskripsi, 50) }}</small>
                            </div>
                            @empty
                            <div class="list-group-item">
                                <p class="mb-0 text-center">Tidak ada emisi pending</p>
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
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('yearlyEmissionChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($emisiPerTahun->pluck('tahun')) !!},
            datasets: [{
                label: 'Total Emisi Karbon (kg CO2)',
                data: {!! json_encode($emisiPerTahun->pluck('total_emisi')) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgb(40, 167, 69)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Emisi (kg CO2)',
                        font: {
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tahun',
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return `Total Emisi: ${value.toLocaleString('id-ID')} kg CO2`;
                        }
                    }
                }
            }
        }
    });

    console.log('Data Tahun:', {!! json_encode($emisiPerTahun->pluck('tahun')) !!});
    console.log('Data Emisi:', {!! json_encode($emisiPerTahun->pluck('total_emisi')) !!});
});
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.card-title {
    font-size: 1rem;
    font-weight: 500;
}
.display-6 {
    font-size: 1.5rem;
    font-weight: 500;
}
.list-group-item {
    border-left: none;
    border-right: none;
}
.list-group-item:first-child {
    border-top: none;
}
.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endpush
@endsection 