@extends('layouts.user')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Edit Data Emisi Karbon</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('emisicarbon.update', $emisiCarbon->kode_emisi_karbon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal_emisi" class="form-label">Tanggal</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_emisi') is-invalid @enderror" 
                                   id="tanggal_emisi" name="tanggal_emisi" 
                                   value="{{ old('tanggal_emisi', $emisiCarbon->tanggal_emisi) }}" required>
                            @error('tanggal_emisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Kategori -->
                        <div class="mb-4">
                            <label for="kategori_emisi_karbon" class="form-label">Kategori Emisi</label>
                            <select class="form-select @error('kategori_emisi_karbon') is-invalid @enderror" 
                                    id="kategori_emisi_karbon" name="kategori_emisi_karbon" required>
                                <option value="">Pilih Kategori Emisi</option>
                                <option value="transportasi" {{ old('kategori_emisi_karbon', $emisiCarbon->kategori_emisi_karbon) == 'transportasi' ? 'selected' : '' }}>Transportasi</option>
                                <option value="listrik" {{ old('kategori_emisi_karbon', $emisiCarbon->kategori_emisi_karbon) == 'listrik' ? 'selected' : '' }}>Penggunaan Listrik</option>
                                <option value="sampah" {{ old('kategori_emisi_karbon', $emisiCarbon->kategori_emisi_karbon) == 'sampah' ? 'selected' : '' }}>Pembuangan Sampah</option>
                                <option value="lainnya" {{ old('kategori_emisi_karbon', $emisiCarbon->kategori_emisi_karbon) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Kadar Emisi -->
                        <div class="mb-4">
                            <label for="kadar_emisi_karbon" class="form-label">Kadar Emisi (kg CO₂)</label>
                            <input type="number" step="0.01" 
                                   class="form-control @error('kadar_emisi_karbon') is-invalid @enderror" 
                                   id="kadar_emisi_karbon" name="kadar_emisi_karbon" 
                                   value="{{ old('kadar_emisi_karbon', $emisiCarbon->kadar_emisi_karbon) }}" required>
                            @error('kadar_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" placeholder="Tambahkan deskripsi..." required>{{ old('deskripsi', $emisiCarbon->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-5">Update</button>
                            <a href="{{ route('emisicarbon.index') }}" class="btn btn-outline-secondary px-5">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
    }

    .btn-success {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.5);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endpush
@endsection
