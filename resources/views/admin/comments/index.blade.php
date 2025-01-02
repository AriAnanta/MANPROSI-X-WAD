@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Data Komentar dari Manager</h5>
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
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">Manager</th>
                                    <th style="width: 10%">Kode Pembelian</th>
                                    <th style="width: 20%">Komentar</th>
                                    <th style="width: 25%">Balasan Admin</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 7%">Tanggal</th>
                                    <th style="width: 8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="manager-text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $comment->manager_name }}">
                                                {{ Str::limit($comment->manager_name, 50) }}
                                            </div>
                                        </td>
                                        <td class="text-break">{{ $comment->kode_pembelian_carbon_credit }}</td>
                                        <td>
                                            <div class="comment-text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $comment->comment }}">
                                                {{ Str::limit($comment->comment, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($comment->admin_reply)
                                                <div class="reply-text" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $comment->admin_reply }}">
                                                    {{ Str::limit($comment->admin_reply, 100) }}
                                                </div>
                                            @else
                                                <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST">
                                                    @csrf
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="reply" class="form-control" 
                                                               placeholder="Tulis balasan..." required>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $comment->status === 'read' ? 'success' : 'warning' }}">
                                                {{ ucfirst($comment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $comment->formatted_date }}</td>
                                        <td class="text-center">
                                            @if($comment->status === 'unread')
                                                <form action="{{ route('admin.comments.mark-as-read', $comment->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i> Tandai Dibaca
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada komentar</td>
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
    .input-group {
        width: 100%;
    }
    .input-group .form-control {
        border-radius: 0.25rem 0 0 0.25rem;
    }
    .input-group .btn {
        border-radius: 0 0.25rem 0.25rem 0;
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        color: white;
    }
    .input-group .btn:hover {
        background: linear-gradient(90deg, #218838, #1e7e34);
    }
    .manager-text, .comment-text, .reply-text {
        max-height: 4.5em;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: left;
        white-space: normal;
        line-height: 1.5;
    }

    td {
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .input-group {
        width: 100%;
    }

    .input-group .form-control {
        border-radius: 0.25rem 0 0 0.25rem;
    }

    .input-group .btn {
        border-radius: 0 0.25rem 0.25rem 0;
        padding: 0.25rem 0.5rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.6em;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.075);
    }
</style>
@endpush
@endsection 