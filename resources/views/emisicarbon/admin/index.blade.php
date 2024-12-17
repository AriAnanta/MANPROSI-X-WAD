@extends('layouts.admin')

@section('title', 'Kelola Emisi Karbon')

@section('content')
<div class="container mt-4">
    <h2>Kelola Emisi Karbon</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Emisi</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Kadar Emisi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($emisiCarbons as $emisi)
                        <tr>
                            <td>{{ $emisi->kode_emisi_karbon }}</td>
                            <td>{{ $emisi->kategori_emisi_karbon }}</td>
                            <td>{{ $emisi->tanggal_emisi }}</td>
                            <td>{{ $emisi->kadar_emisi_karbon }}</td>
                            <td>
                                <span class="badge bg-{{ $emisi->status == 'approved' ? 'success' : ($emisi->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($emisi->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.emissions.edit_status', $emisi->kode_emisi_karbon) }}" 
                                   class="btn btn-sm btn-primary">
                                    Edit Status
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $emisiCarbons->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 