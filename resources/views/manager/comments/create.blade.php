@extends('layouts.manager')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Input Komentar Carbon Credit</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('manager.comments.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="kode_pembelian_carbon_credit" class="form-label">Pilih Pembelian Carbon Credit</label>
                            <select name="kode_pembelian_carbon_credit" id="kode_pembelian_carbon_credit" class="form-select @error('kode_pembelian_carbon_credit') is-invalid @enderror" required>
                                <option value="">Pilih Pembelian</option>
                                @forelse($pembelianList as $pembelian)
                                    <option value="{{ $pembelian->kode_pembelian_carbon_credit }}">
                                        {{ $pembelian->kode_pembelian_carbon_credit }} | 
                                        Jumlah: {{ number_format($pembelian->jumlah_kompensasi, 2) }} ton CO2e | 
                                        Tanggal: {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian_carbon_credit)->format('d/m/Y') }}
                                    </option>
                                @empty
                                    <option disabled>Tidak ada data pembelian carbon credit</option>
                                @endforelse
                            </select>
                            @error('kode_pembelian_carbon_credit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="form-label">Komentar</label>
                            <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror" required placeholder="Tulis komentar...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-5">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                            <a href="{{ route('manager.comments.index') }}" class="btn btn-outline-secondary px-5">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
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