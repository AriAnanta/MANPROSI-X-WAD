@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Emisi Karbon Anda</h5>
                <p class="card-text">10,000 kg CO<sub>2</sub></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Carbon Credit Anda</h5>
                <p class="card-text">20 Credits</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Riwayat Emisi Karbon Anda
            </div>
            <div class="card-body">
                <canvas id="userEmissionsChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection 