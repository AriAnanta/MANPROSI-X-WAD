@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Input Data Emisi Karbon</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('emisicarbon.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="tanggal_emisi" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal_emisi') is-invalid @enderror" 
                                   id="tanggal_emisi" name="tanggal_emisi" value="{{ old('tanggal_emisi') }}" required>
                            @error('tanggal_emisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori_emisi_karbon" class="form-label">Kategori Emisi</label>
                            <select class="form-select @error('kategori_emisi_karbon') is-invalid @enderror" 
                                    id="kategori_emisi_karbon" name="kategori_emisi_karbon" required>
                                <option value="">Pilih Kategori Emisi</option>
                                <option value="transportasi">Transportasi</option>
                                <option value="listrik">Penggunaan Listrik</option>
                                <option value="sampah">Pembuangan Sampah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            @error('kategori_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kadar_emisi_karbon" class="form-label">Kadar Emisi (kg CO2)</label>
                            <input type="number" step="0.01" class="form-control @error('kadar_emisi_karbon') is-invalid @enderror" 
                                   id="kadar_emisi_karbon" name="kadar_emisi_karbon" value="{{ old('kadar_emisi_karbon') }}" required>
                            @error('kadar_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                            <a href="{{ route('emisicarbon.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
