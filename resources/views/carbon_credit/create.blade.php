@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Input Pembelian Carbon Credit</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('carbon_credit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Input Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal_pembelian_carbon_credit" class="form-label">Tanggal Pembelian</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_pembelian_carbon_credit') is-invalid @enderror" 
                                   id="tanggal_pembelian_carbon_credit" 
                                   name="tanggal_pembelian_carbon_credit" 
                                   value="{{ old('tanggal_pembelian_carbon_credit') }}" 
                                   required>
                            @error('tanggal_pembelian_carbon_credit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Jumlah -->
                        <div class="mb-4">
                            <label for="jumlah_pembelian_carbon_credit" class="form-label">Jumlah (kg CO₂)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('jumlah_pembelian_carbon_credit') is-invalid @enderror" 
                                   id="jumlah_pembelian_carbon_credit" 
                                   name="jumlah_pembelian_carbon_credit" 
                                   value="{{ old('jumlah_pembelian_carbon_credit') }}" 
                                   required>
                            @error('jumlah_pembelian_carbon_credit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Bukti Pembelian -->
                        <div class="mb-4">
                            <label for="bukti_pembelian" class="form-label">Bukti Pembelian</label>
                            <input type="file" 
                                   class="form-control @error('bukti_pembelian') is-invalid @enderror" 
                                   id="bukti_pembelian" 
                                   name="bukti_pembelian" 
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   required>
                            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</small>
                            @error('bukti_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Deskripsi -->
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
                            <a href="{{ route('carbon_credit.index') }}" class="btn btn-outline-secondary px-5">Kembali</a>
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