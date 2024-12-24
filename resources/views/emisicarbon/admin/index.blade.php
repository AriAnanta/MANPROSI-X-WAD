@extends('layouts.admin')

@section('title', 'Kelola Emisi Karbon')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Kelola Emisi Karbon</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Kadar Emisi (kg CO2)</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emisiCarbons as $emisi)
                                    <tr>
                                        <td>{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                                        <td>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                                        <td class="text-end">{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                                        <td>{{ $emisi->deskripsi }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $emisi->status === 'approved' ? 'success' : ($emisi->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($emisi->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.emissions.edit_status', $emisi->kode_emisi_karbon) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit Status
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data emisi karbon</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-group .btn i {
        font-size: 0.875rem;
    }
    table th, table td {
        text-align: center;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.7em;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endpush
