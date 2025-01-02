@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Kelola Pengguna</h5>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Tambah Pengguna
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
                                    <th style="width: 50px">No</th>
                                    <th style="width: 120px">Kode User</th>
                                    <th style="width: 200px">Nama</th>
                                    <th style="width: 200px">Email</th>
                                    <th style="width: 120px">No. Telepon</th>
                                    <th style="width: 100px">Terdaftar</th>
                                    <th style="width: 180px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center text-nowrap">{{ $user->kode_user }}</td>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $user->nama_user }}">
                                            {{ $user->nama_user }}
                                        </td>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $user->email }}">
                                            {{ $user->email }}
                                        </td>
                                        <td class="text-center text-nowrap">{{ $user->no_telepon }}</td>
                                        <td class="text-center text-nowrap">{{ $user->formatted_date }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pengguna</td>
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
@endsection

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
</style>
@endpush 