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
                                    <th style="width: 150px">Kategori</th>
                                    <th style="width: 100px">Tanggal</th>
                                    <th style="width: 150px">Kadar Emisi (kg CO₂)</th>
                                    <th style="width: 300px">Deskripsi</th>
                                    <th style="width: 100px">Status</th>
                                    <th style="width: 180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emisiCarbons as $emisi)
                                    <tr>
                                        <td class="text-nowrap">{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                                        <td class="text-center text-nowrap">{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                                        <td class="text-end text-nowrap">{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            data-bs-html="true"
                                            title="{{ $emisi->deskripsi }}">
                                            {{ $emisi->deskripsi }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $emisi->status === 'approved' ? 'success' : ($emisi->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($emisi->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.emissions.edit-status', $emisi->kode_emisi_karbon) }}" 
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
    .table {
        table-layout: fixed;
        width: 100%;
    }

    .table td {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tooltip-large .tooltip-inner {
        max-width: 400px;
        padding: 10px 15px;
        background-color: rgba(0, 0, 0, 0.9);
        font-size: 14px;
        line-height: 1.4;
        text-align: left;
        word-break: break-word;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    [data-bs-toggle="tooltip"] {
        cursor: help;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            min-width: 1200px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Inisialisasi tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl, {
        template: '<div class="tooltip tooltip-large" role="tooltip">' +
                 '<div class="tooltip-arrow"></div>' +
                 '<div class="tooltip-inner"></div>' +
                 '</div>'
    });
});
</script>
@endpush
