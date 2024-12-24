@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Data Pembelian Carbon Credit</h5>
                    <a href="{{ route('carbon_credit.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pembelian
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
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah (kg CO2)</th>
                                    <th>Deskripsi</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($carbon_credit as $index => $credit)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $credit->kode_pembelian_carbon_credit }}</td>
                                        <td>{{ date('d/m/Y', strtotime($credit->tanggal_pembelian_carbon_credit)) }}</td>
                                        <td class="text-end">{{ number_format($credit->jumlah_pembelian_carbon_credit, 2) }}</td>
                                        <td>{{ $credit->deskripsi }}</td>
                                        <td class="text-center">
                                            @if($credit->bukti_pembelian)
                                                <a href="{{ Storage::url($credit->bukti_pembelian) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   target="_blank">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Tidak ada bukti</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('carbon_credit.edit', $credit->kode_pembelian_carbon_credit) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('carbon_credit.destroy', $credit->kode_pembelian_carbon_credit) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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

@push('styles')
<style>
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-group .btn i {
        font-size: 0.875rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endpush
@endsection 