@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-center fw-semibold">Input Notifikasi</h4>
                    <a href="{{ route('notifikasi.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-list"></i> Daftar Notifikasi
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('notifikasi.store') }}" method="POST">
                        @csrf
                        <!-- Dropdown Kode User -->
                        <div class="mb-4">
                            <label for="kode_user" class="form-label">Pilih Pengguna</label>
                            <select class="form-control @error('kode_user') is-invalid @enderror" id="kode_user" name="kode_user" required>
                                <option value="">-- Pilih Pengguna --</option>
                                <option value="all">Semua Pengguna</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->kode_user }}">{{ $user->nama_user }}</option>
                                @endforeach
                            </select>
                            @error('kode_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label for="kategori_notifikasi" class="form-label">Kategori</label>
                            <select class="form-control @error('kategori_notifikasi') is-invalid @enderror" 
                                    id="kategori_notifikasi" 
                                    name="kategori_notifikasi" 
                                    required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Input" {{ old('kategori_notifikasi') == 'Input' ? 'selected' : '' }}>Input</option>
                                <option value="Update" {{ old('kategori_notifikasi') == 'Update' ? 'selected' : '' }}>Update</option>
                                <option value="Pengumuman" {{ old('kategori_notifikasi') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            </select>
                            @error('kategori_notifikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal') }}" 
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4" 
                                      required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-5">Simpan</button>
                            <a href="{{ route('notifikasi.index') }}" class="btn btn-outline-secondary px-5">Kembali</a>
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
    }
    .card {
        border-radius: 15px;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endpush
@endsection
