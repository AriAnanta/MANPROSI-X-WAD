@extends('layouts.admin')

@section('title', 'Edit Status Emisi Karbon')

@section('content')
<div class="container mt-4">
    <h2>Edit Status Emisi Karbon</h2>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.emissions.update_status', $emisiCarbon->kode_emisi_karbon) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Emisi Karbon</label>
                        <input type="text" class="form-control" value="{{ $emisiCarbon->kode_emisi_karbon }}" disabled>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Kategori Emisi</label>
                        <input type="text" class="form-control" value="{{ $emisiCarbon->kategori_emisi_karbon }}" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Emisi</label>
                        <input type="date" class="form-control" value="{{ $emisiCarbon->tanggal_emisi }}" disabled>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Kadar Emisi Karbon</label>
                        <input type="number" class="form-control" value="{{ $emisiCarbon->kadar_emisi_karbon }}" disabled>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" rows="3" disabled>{{ $emisiCarbon->deskripsi }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode Manager</label>
                        <input type="text" class="form-control" value="{{ $emisiCarbon->kode_manager }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Kode User</label>
                        <input type="text" class="form-control" value="{{ $emisiCarbon->kode_user }}" disabled>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Kode Admin</label>
                        <input type="text" class="form-control" value="{{ $emisiCarbon->kode_admin }}" disabled>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="approved" {{ $emisiCarbon->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ $emisiCarbon->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $emisiCarbon->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Dibuat</label>
                    <input type="text" class="form-control" value="{{ $emisiCarbon->created_at }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Terakhir Diupdate</label>
                    <input type="text" class="form-control" value="{{ $emisiCarbon->updated_at }}" disabled>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.emissions.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection