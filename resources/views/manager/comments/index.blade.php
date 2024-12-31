@extends('layouts.manager')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Data Komentar Carbon Credit</h5>
                    <a href="{{ route('manager.comments.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Komentar
                    </a>
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
                                    <th>No</th>
                                    <th>Kode Pembelian</th>
                                    <th>Komentar</th>
                                    <th>Balasan Admin</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr class="{{ $comment->admin_reply && !$comment->manager_read ? 'table-warning' : '' }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $comment->kode_pembelian_carbon_credit }}</td>
                                        <td>{{ $comment->comment }}</td>
                                        <td>
                                            @if($comment->admin_reply)
                                                {{ $comment->admin_reply }}
                                                @if(!$comment->manager_read)
                                                    <span class="badge bg-warning">Baru</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Belum ada balasan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $comment->status === 'read' ? 'success' : 'warning' }}">
                                                {{ ucfirst($comment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('manager.comments.edit', $comment) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('manager.comments.destroy', $comment) }}" 
                                                      method="POST" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada komentar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $comments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    .btn-group .btn i {
        font-size: 0.875rem;
    }
    table th, table td {
        text-align: center;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.7em;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endpush
@endsection 