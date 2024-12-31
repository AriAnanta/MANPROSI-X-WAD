@extends('layouts.manager')

@section('title', 'Manajemen Faktor Emisi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Manajemen Faktor Emisi</h5>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createFaktorEmisi">
                        <i class="bi bi-plus-circle"></i> Tambah Faktor Emisi
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th class="text-center">Nilai Faktor</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faktorEmisis as $index => $faktor)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $faktor->kategori_emisi_karbon }}</td>
                                    <td>{{ $faktor->sub_kategori }}</td>
                                    <td class="text-center">{{ $faktor->nilai_faktor }}</td>
                                    <td class="text-center">{{ $faktor->satuan }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editFaktorEmisi"
                                                data-id="{{ $faktor->id }}"
                                                data-kategori="{{ $faktor->kategori_emisi_karbon }}"
                                                data-sub-kategori="{{ $faktor->sub_kategori }}"
                                                data-nilai="{{ $faktor->nilai_faktor }}"
                                                data-satuan="{{ $faktor->satuan }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteFaktorEmisi"
                                                data-id="{{ $faktor->id }}">
                                            <i class="bi bi-trash"></i> Hapus
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
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title fw-bold">Tambah Faktor Emisi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('manager.faktor-emisi.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label">Kategori Emisi</label>
                        <input type="text" class="form-control" name="kategori_emisi_karbon" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Sub Kategori</label>
                        <input type="text" class="form-control" name="sub_kategori" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Nilai Faktor</label>
                        <input type="number" step="0.0001" class="form-control" name="nilai_faktor" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" name="satuan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title fw-bold">Edit Faktor Emisi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
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
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteFaktorEmisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title fw-bold">Hapus Faktor Emisi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus faktor emisi ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .btn-group .btn i {
        font-size: 0.875rem;
    }
    
    table th, table td {
        vertical-align: middle;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    
    .btn-light {
        background: #ffffff;
        border: 2px solid #ffffff;
        transition: all 0.2s ease;
    }

    .btn-light:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }

    .btn-warning {
        color: #fff;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        color: #fff;
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-sm {
        margin: 0 2px;
    }

    /* Tambahan style untuk modal */
    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
    }

    .form-label {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.6rem 1rem;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .bg-gradient-danger {
        background: linear-gradient(90deg, #dc3545, #c82333);
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }
</style>
@endpush

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

@endsection
