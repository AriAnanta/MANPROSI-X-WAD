@extends('layouts.manager')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Summary Cards -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0 fw-bold">Ringkasan Pembelian Carbon Credit</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-primary mb-3">Total Pembelian</h6>
                                    <p class="card-text h3 text-primary">{{ $summary['total_pembelian'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-success mb-3">Total Kompensasi</h6>
                                    <p class="card-text h3 text-success">{{ number_format($summary['total_kompensasi'], 3) }} ton</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-info mb-3">Kompensasi Selesai</h6>
                                    <p class="card-text h3 text-info">{{ $summary['completed_kompensasi'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-warning mb-3">Kompensasi Pending</h6>
                                    <p class="card-text h3 text-warning">{{ $summary['pending_kompensasi'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Daftar Pembelian Carbon Credit</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th class="d-none d-md-table-cell">Kode Pembelian</th>
                                    <th>Kategori</th>
                                    <th class="d-none d-md-table-cell">Sub Kategori</th>
                                    <th>Jumlah</th>
                                    <th class="d-none d-md-table-cell">Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carbonCredits as $credit)
                                <tr>
                                    <td class="d-none d-md-table-cell">{{ $credit['kode_pembelian'] }}</td>
                                    <td>{{ Str::limit($credit['kategori'], 20) }}</td>
                                    <td class="d-none d-md-table-cell">{{ $credit['sub_kategori'] }}</td>
                                    <td class="text-end">{{ number_format($credit['jumlah_kompensasi'], 1) }}</td>
                                    <td class="d-none d-md-table-cell">{{ date('d/m/y', strtotime($credit['tanggal_pembelian'])) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $credit['status_kompensasi'] === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($credit['status_kompensasi']) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $credit['kode_pembelian'] }}">
                                            <i class="fas fa-eye d-block d-md-none"></i>
                                            <span class="d-none d-md-block">Detail</span>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data pembelian carbon credit</td>
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

<!-- Detail Modals -->
@foreach($carbonCredits as $credit)
<div class="modal fade" id="detailModal{{ $credit['kode_pembelian'] }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title fw-bold">Detail Pembelian Carbon Credit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-success">Kode Pembelian:</label>
                            <p class="border-bottom pb-2">{{ $credit['kode_pembelian'] }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-success">Kode Kompensasi:</label>
                            <p class="border-bottom pb-2">{{ $credit['kode_kompensasi'] }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-success">Kategori:</label>
                            <p class="border-bottom pb-2">{{ $credit['kategori'] }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-success">Sub Kategori:</label>
                            <p class="border-bottom pb-2">{{ $credit['sub_kategori'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold text-success">Jumlah Kompensasi:</label>
                            <p class="border-bottom pb-2">{{ number_format($credit['jumlah_kompensasi'], 3) }} ton</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-success">Tanggal Pembelian:</label>
                            <p class="border-bottom pb-2">{{ date('d/m/Y', strtotime($credit['tanggal_pembelian'])) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-success">Status:</label>
                            <p class="border-bottom pb-2">
                                <span class="badge bg-{{ $credit['status_kompensasi'] === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($credit['status_kompensasi']) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="fw-bold text-success">Deskripsi:</label>
                    <div class="border rounded p-3 bg-light">
                        {{ $credit['deskripsi'] ?? 'Tidak ada deskripsi' }}
                    </div>
                </div>

                @if($credit['bukti_pembelian'])
                <div class="mb-3">
                    <label class="fw-bold text-success">Bukti Pembelian:</label>
                    <div class="mt-2">
                        <a href="{{ $credit['bukti_pembelian'] }}" 
                           target="_blank" 
                           class="btn btn-success">
                            <i class="fas fa-file-download"></i> Bukti Pembelian
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<style>
    .card {
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn i {
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection