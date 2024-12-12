@extends('layouts.app')

@section('content')
<div class="container mt-6">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center mb-4 text-success">Edit Emisi Karbon</h2>

            <div class="card shadow-lg" style="border-radius: 15px;">
                <div class="card-body" style="background-color: #e8f5e9;">
                    <form action="{{ route('emisicarbon.update', $emisiCarbon->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Kode Emisi Karbon -->
                            <div class="col-md-6 mb-3">
                                <label for="kode_emisi_karbon" class="form-label text-success">Kode Emisi Karbon</label>
                                <input type="text" class="form-control" id="kode_emisi_karbon" name="kode_emisi_karbon" value="{{ $emisiCarbon->kode_emisi_karbon }}" required>
                            </div>

                            <!-- Kategori Emisi Karbon -->
                            <div class="col-md-6 mb-3">
                                <label for="kategori_emisi_karbon" class="form-label text-success">Kategori Emisi Karbon</label>
                                <input type="text" class="form-control" id="kategori_emisi_karbon" name="kategori_emisi_karbon" value="{{ $emisiCarbon->kategori_emisi_karbon }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tanggal Emisi -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_emisi" class="form-label text-success">Tanggal Emisi</label>
                                <input type="date" class="form-control" id="tanggal_emisi" name="tanggal_emisi" value="{{ $emisiCarbon->tanggal_emisi }}" required>
                            </div>

                            <!-- Kadar Emisi Karbon -->
                            <div class="col-md-6 mb-3">
                                <label for="kadar_emisi_karbon" class="form-label text-success">Kadar Emisi Karbon</label>
                                <input type="number" class="form-control" id="kadar_emisi_karbon" name="kadar_emisi_karbon" value="{{ $emisiCarbon->kadar_emisi_karbon }}" required>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label text-success">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" required rows="4">{{ $emisiCarbon->deskripsi }}</textarea>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label text-success">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Aktif" {{ $emisiCarbon->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ $emisiCarbon->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <!-- Button Submit -->
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" class="btn btn-success btn-lg" style="border-radius: 25px;">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
