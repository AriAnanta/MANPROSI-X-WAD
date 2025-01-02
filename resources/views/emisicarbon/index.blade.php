@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Data Emisi Karbon</h5>
                    <a href="{{ route('emisicarbon.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Data
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
                                    <th>Tanggal</th>
                                    <th>Kategori Emisi</th>
                                    <th>Sub Kategori</th>
                                    <th>Nilai Aktivitas</th>
                                    <th>Kadar Emisi (kg CO₂)</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emisiCarbons as $index => $emisi)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                                        <td>{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                                        <td>{{ ucfirst($emisi->sub_kategori) }}</td>
                                        <td class="col-nilai-aktivitas">{{ number_format($emisi->nilai_aktivitas, 2) }} {{ $emisi->satuan }}</td>
                                        <td class="text-center">
                                            {{ number_format($emisi->kadar_emisi_karbon, 2) }} kg CO₂e
                                        </td>
                                        <td class="col-deskripsi">{{ $emisi->deskripsi }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $emisi->status === 'pending' ? 'warning' : ($emisi->status === 'rejected' ? 'danger' : 'success') }}">
                                                {{ ucfirst($emisi->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('emisicarbon.edit', $emisi->kode_emisi_karbon) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('emisicarbon.destroy', $emisi->kode_emisi_karbon) }}" 
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
                                        <td colspan="9" class="text-center">Tidak ada data emisi karbon</td>
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

<!-- Script untuk konfirmasi hapus -->
@push('scripts')
<script>
function hapusData(kodeEmisi) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        document.getElementById('form-hapus-' + kodeEmisi).submit();
    }
}
</script>
@endpush
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
    .col-deskripsi {
        word-wrap: break-word;
        white-space: pre-wrap;
        max-width: 300px;
    }
    .col-nilai-aktivitas {
        word-wrap: break-word;
        white-space: pre-wrap;
        max-width: 150px;
        text-align: right;
        padding-right: 10px;
    }
</style>
@endpush
