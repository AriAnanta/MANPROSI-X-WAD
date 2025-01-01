@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Histori Notifikasi</h5>
                    <div>
                        <a href="{{ route('notifikasi.report') }}" class="btn btn-light btn-sm me-2" target="_blank">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </a>
                        <a href="{{ route('notifikasi.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus"></i> Tambah Notifikasi
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('notifikasi.index') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="tujuan" class="form-label">Tujuan</label>
                                    <select name="tujuan" id="tujuan" class="form-select">
                                        <option value="">Semua Tujuan</option>
                                        @foreach($penggunas as $pengguna)
                                            <option value="{{ $pengguna->kode_user }}" {{ request('tujuan') == $pengguna->kode_user ? 'selected' : '' }}>
                                                {{ $pengguna->nama_user }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <select name="kategori" id="kategori" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        <option value="Input" {{ request('kategori') == 'Input' ? 'selected' : '' }}>Input</option>
                                        <option value="Update" {{ request('kategori') == 'Update' ? 'selected' : '' }}>Update</option>
                                        <option value="Pengumuman" {{ request('kategori') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                    <a href="{{ route('notifikasi.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th style="width: 50px">No</th>
                                    <th style="width: 150px">Tujuan</th>
                                    <th style="width: 100px">Kategori</th>
                                    <th style="width: 100px">Tanggal</th>
                                    <th style="width: 300px">Deskripsi</th>
                                    <th style="width: 180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifikasi as $index => $notif)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-nowrap">{{ $notif->pengguna->nama_user ?? ($notif->kode_user ?? 'Semua') }}</td>
                                        <td class="text-center">{{ $notif->kategori_notifikasi }}</td>
                                        <td class="text-center text-nowrap">{{ date('d/m/Y', strtotime($notif->tanggal)) }}</td>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            data-bs-html="true"
                                            title="{{ $notif->deskripsi }}">
                                            {{ $notif->deskripsi }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('notifikasi.edit', $notif->id) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('notifikasi.destroy', $notif->id) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada notifikasi</td>
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

@push('styles')
<style>
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
    .form-label {
        font-weight: 500;
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
@endsection
