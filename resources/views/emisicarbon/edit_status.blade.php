@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Edit Status Emisi Karbon</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.emissions.update-status', $emisiCarbon->kode_emisi_karbon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Kode Emisi Karbon</label>
                            <input type="text" class="form-control" value="{{ $emisiCarbon->kode_emisi_karbon }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Kategori Emisi</label>
                            <input type="text" class="form-control" value="{{ $emisiCarbon->kategori_emisi_karbon }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tanggal Emisi</label>
                            <input type="date" class="form-control" value="{{ $emisiCarbon->tanggal_emisi }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Sub Kategori</label>
                            <input type="text" class="form-control" value="{{ ucfirst($emisiCarbon->sub_kategori) }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nilai Aktivitas</label>
                            <div class="input-group">
                                <input type="number" class="form-control" value="{{ $emisiCarbon->nilai_aktivitas }}" disabled>
                                <span class="input-group-text">{{ $emisiCarbon->satuan ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Kadar Emisi Karbon</label>
                            <div class="input-group">
                                <input type="number" class="form-control" value="{{ $emisiCarbon->kadar_emisi_karbon }}" disabled>
                                <span class="input-group-text">kg CO₂</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" rows="4" disabled>{{ $emisiCarbon->deskripsi }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label for="status" class="form-label">Status Emisi Karbon</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="approved" {{ $emisiCarbon->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ $emisiCarbon->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ $emisiCarbon->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tanggal Dibuat</label>
                            <input type="text" class="form-control" value="{{ $emisiCarbon->created_at }}" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Terakhir Diupdate</label>
                            <input type="text" class="form-control" value="{{ $emisiCarbon->updated_at }}" disabled>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.emissions.index') }}" class="btn btn-outline-secondary px-5">Kembali</a>
                            <button type="submit" class="btn btn-success px-5">Update Status</button>
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