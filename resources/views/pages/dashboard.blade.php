@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Carbon Emission</h5>
                <p class="card-text">25,000 kg CO<sub>2</sub></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Pending Actions</h5>
                <p class="card-text">3 Pending Reports</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Credits Purchased</h5>
                <p class="card-text">50 Carbon Credits</p>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Total Emission -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Total Emissions/Offset
            </div>
            <div class="card-body">
                <canvas id="emissionsChart"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    var ctx = document.getElementById('emissionsChart').getContext('2d');
    var emissionsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'],
            datasets: [{
                label: 'Emissions',
                data: [12000, 15000, 10000, 25000, 20000, 18000, 14000, 23000, 19000, 16000, 22000, 24000], // Data emisi
                backgroundColor: 'rgba(255, 99, 132, 0.2)', // Warna bar merah untuk emisi
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Offsets',
                data: [10000, 13000, 11000, 20000, 15000, 16000, 13000, 18000, 16000, 17000, 21000, 23000], // Data offset
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna bar hijau untuk offset
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Responsif sesuai ukuran layar
            maintainAspectRatio: false, // Menghilangkan pengaturan rasio aspek tetap
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
