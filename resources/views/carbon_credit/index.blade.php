@extends('layouts.admin')

@section('title', 'Data Pembelian Carbon Credit')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Data Pembelian Carbon Credit</h5>
                    <a href="{{ route('carbon_credit.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle"></i> Tambah Pembelian
                    </a>
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
                                <tr class="text-center">
                                    <th style="width: 50px">No</th>
                                    <th style="width: 120px">Kode</th>
                                    <th style="width: 100px">Tanggal</th>
                                    <th style="width: 120px">Jumlah (kg CO₂e)</th>
                                    <th style="width: 200px">Deskripsi</th>
                                    <th style="width: 80px">Bukti</th>
                                    <th style="width: 180px">Penyedia</th>
                                    <th style="width: 100px">Total Harga</th>
                                    <th style="width: 180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carbon_credit as $index => $credit)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-nowrap">{{ $credit->kode_pembelian_carbon_credit }}</td>
                                        <td class="text-center text-nowrap">{{ date('d/m/Y', strtotime($credit->tanggal_pembelian_carbon_credit)) }}</td>
                                        <td class="text-end text-nowrap">{{ number_format($credit->jumlah_kompensasi, 0) }}</td>
                                        <td class="text-truncate position-relative" 
                                            style="max-width: 200px" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            data-bs-html="true"
                                            title="{{ $credit->deskripsi }}">
                                            {{ $credit->deskripsi }}
                                        </td>
                                        <td class="text-center">
                                            @if($credit->bukti_pembelian)
                                                <a href="{{ asset('storage/' . $credit->bukti_pembelian) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   target="_blank">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-truncate position-relative"
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            data-bs-html="true"
                                            title="{{ $credit->penyediaCarbonCredit->nama_penyedia }}">
                                            {{ $credit->penyediaCarbonCredit->nama_penyedia }}
                                        </td>
                                        <td class="text-end text-nowrap">
                                            {{ $credit->penyediaCarbonCredit->mata_uang }} 
                                            {{ number_format($credit->total_harga, 2) }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('carbon_credit.edit', $credit->kode_pembelian_carbon_credit) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-id="{{ $credit->kode_pembelian_carbon_credit }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data pembelian carbon credit</td>
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

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-warning display-4"></i>
                    </div>
                    <p class="text-center mb-1 fw-bold">Apakah Anda yakin ingin menghapus pembelian carbon credit ini?</p>
                    <p class="text-center text-muted mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-light {
        background: #ffffff;
        border: 2px solid #ffffff;
        transition: all 0.2s ease;
    }

    .btn-light:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }

    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-group .btn i {
        font-size: 0.875rem;
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

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100%;
    }

    td[title]:hover {
        position: relative;
    }

    td[title]:hover::after {
        content: attr(title);
        position: absolute;
        left: 0;
        top: 100%;
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        z-index: 1000;
        white-space: normal;
        max-width: 300px;
        word-wrap: break-word;
    }

    .btn-group {
        white-space: nowrap;
        width: 130px;
    }

    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-group .btn i {
        font-size: 0.875rem;
        margin-right: 2px;
    }

    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            min-width: 1200px;
        }
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

    .text-truncate {
        max-width: 100%;
        display: block;
    }

    [data-bs-toggle="tooltip"] {
        cursor: help;
    }

    .tooltip {
        z-index: 9999;
    }

    .tooltip.fade.show {
        opacity: 1;
        transition: opacity 0.2s ease-in-out;
    }

    @media (max-width: 768px) {
        .tooltip-large .tooltip-inner {
            max-width: 300px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Inisialisasi semua tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl, {
        template: '<div class="tooltip tooltip-large" role="tooltip">' +
                 '<div class="tooltip-arrow"></div>' +
                 '<div class="tooltip-inner"></div>' +
                 '</div>'
    });
});

// Script untuk Delete Modal
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('deleteForm').action = `/admin/carbon-credit/${id}`;
    });
});
</script>
@endpush
@endsection 