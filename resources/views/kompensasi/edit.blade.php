@extends('layouts.manager')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Kompensasi</h5>
                    <a href="{{ route('manager.kompensasi.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('manager.kompensasi.update', ['kompensasi' => $kompensasi->kode_kompensasi]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2">Informasi Emisi</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="200">Kode Emisi</td>
                                        <td>: {{ $kompensasi->kode_emisi_karbon }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kategori</td>
                                        <td>: {{ ucfirst($kompensasi->kategori_emisi) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sub Kategori</td>
                                        <td>: {{ ucfirst($kompensasi->sub_kategori) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kadar Emisi</td>
                                        <td>: {{ number_format($kompensasi->kadar_emisi_ton, 2) }} ton CO₂e</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2">Edit Kompensasi</h6>
                                <div class="form-group mb-3">
                                    <label>Jumlah Kompensasi (ton CO₂e)</label>
                                    <input type="number" name="jumlah_kompensasi" 
                                           class="form-control @error('jumlah_kompensasi') is-invalid @enderror"
                                           value="{{ old('jumlah_kompensasi', number_format($kompensasi->jumlah_ton, 2)) }}"
                                           step="0.01" min="0.01" required>
                                    @error('jumlah_kompensasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Update Kompensasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 