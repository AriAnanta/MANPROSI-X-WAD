@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Emisi Karbon</h5>
                    <a href="{{ route('emisicarbon.create') }}" class="btn btn-light">
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
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kategori Emisi</th>
                                    <th>Kadar Emisi (kg CO2)</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th width="150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emisiCarbons as $index => $emisi)
                                    <tr>
                                        <td>{{ $index + $emisiCarbons->firstItem() }}</td>
                                        <td>{{ \Carbon\Carbon::parse($emisi->tanggal_emisi)->format('d/m/Y') }}</td>
                                        <td>{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                                        <td>{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                                        <td>{{ $emisi->deskripsi }}</td>
                                        <td>
                                            <span class="badge bg-{{ $emisi->status == 'pending' ? 'warning' : 'success' }}">
                                                {{ ucfirst($emisi->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('emisicarbon.edit', $emisi->kode_emisi_karbon) }}" 
                                                   class="btn btn-warning me-2">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('emisicarbon.destroy', $emisi->kode_emisi_karbon) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data emisi karbon</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $emisiCarbons->links() }}
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
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .btn-group .btn i {
        font-size: 0.9rem;
    }
    table th:last-child,
    table td:last-child {
        width: 200px;
        min-width: 200px;
    }
    .btn-group .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        transition: all 0.2s;
    }
    .btn-group form {
        margin: 0;
    }
    .btn-group form button {
        border-radius: 0.25rem;
    }
</style>
@endpush
