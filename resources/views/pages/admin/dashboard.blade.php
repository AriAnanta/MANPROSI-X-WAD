@extends('layouts.admin')

@section('title', 'Dashboard Admin - GreenLedger')

@section('content')
<div class="container-fluid">
    <main class="px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 text-success">Dashboard Admin</h1>
        </div>

        <!-- Kartu Informasi Ringkasan -->
        <div class="row g-2 mb-4">
            <div class="col-12 col-sm-6 col-md-3 mb-3">
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
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="card bg-dark text-white h-100 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-globe-americas fa-3x mb-3"></i>
                        <h5 class="card-title">Total Emisi Carbon (Approved)</h5>
                        <p class="card-text display-6">
                            {{ number_format($totalEmissionsApprovedTon, 3) }}
                            <small class="fs-6">ton CO<sub>2</sub>e</small>
                        </p>
                        <p class="card-text">
                            <small>
                                Konversi dari {{ number_format($totalEmissionsApprovedTon * 1000, 2) }} kg CO₂
                            </small>
                        </p>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle functionality
    const toggleSidebar = document.querySelector('.navbar-toggler');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.createElement('div');
    
    overlay.classList.add('sidebar-overlay');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1035;
        display: none;
    `;
    
    document.body.appendChild(overlay);

    toggleSidebar.addEventListener('click', function() {
        sidebar.classList.toggle('show');
        if (sidebar.classList.contains('show')) {
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    });

    overlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', function(e) {
        if (!sidebar.contains(e.target) && !toggleSidebar.contains(e.target)) {
            sidebar.classList.remove('show');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('show');
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
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
