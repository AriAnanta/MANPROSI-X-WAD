@extends('layouts.manager')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2 text-success">Manajemen Faktor Emisi</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFaktorEmisi">
            <i class="bi bi-plus-circle"></i> Tambah Faktor Emisi
        </button>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Tabel Faktor Emisi -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Sub Kategori</th>
                            <th>Nilai Faktor</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faktorEmisis as $index => $faktor)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $faktor->kategori_emisi_karbon }}</td>
                            <td>{{ $faktor->sub_kategori }}</td>
                            <td>{{ $faktor->nilai_faktor }}</td>
                            <td>{{ $faktor->satuan }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editFaktorEmisi"
                                        data-id="{{ $faktor->id }}"
                                        data-kategori="{{ $faktor->kategori_emisi_karbon }}"
                                        data-sub-kategori="{{ $faktor->sub_kategori }}"
                                        data-nilai="{{ $faktor->nilai_faktor }}"
                                        data-satuan="{{ $faktor->satuan }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteFaktorEmisi"
                                        data-id="{{ $faktor->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data faktor emisi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('manager.faktor-emisi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Faktor Emisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori Emisi</label>
                        <input type="text" class="form-control" name="kategori_emisi_karbon" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Kategori</label>
                        <input type="text" class="form-control" name="sub_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Faktor</label>
                        <input type="number" step="0.0001" class="form-control" name="nilai_faktor" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" name="satuan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Faktor Emisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori Emisi</label>
                        <input type="text" class="form-control" name="kategori_emisi_karbon" id="edit_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Kategori</label>
                        <input type="text" class="form-control" name="sub_kategori" id="edit_sub_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Faktor</label>
                        <input type="number" step="0.0001" class="form-control" name="nilai_faktor" id="edit_nilai" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" name="satuan" id="edit_satuan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Faktor Emisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus faktor emisi ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script untuk Edit Modal
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const kategori = this.dataset.kategori;
            const subKategori = this.dataset.subKategori;
            const nilai = this.dataset.nilai;
            const satuan = this.dataset.satuan;

            document.getElementById('edit_kategori').value = kategori;
            document.getElementById('edit_sub_kategori').value = subKategori;
            document.getElementById('edit_nilai').value = nilai;
            document.getElementById('edit_satuan').value = satuan;
            document.getElementById('editForm').action = `/manager/faktor-emisi/${id}`;
        });
    });

    // Script untuk Delete Modal
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('deleteForm').action = `/manager/faktor-emisi/${id}`;
        });
    });
</script>
@endpush
<<<<<<< HEAD
@endsection
=======
@endsection 
>>>>>>> fa3fd670cc780c4d9894654f8e0b5205c88b78c3
