@extends('layouts.app')

@section('content')
<div class="container mt-6">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center mb-4 text-success">Data Emisi Karbon</h2>

            <div class="card shadow-lg" style="border-radius: 15px;">
                <div class="card-body" style="background-color: #e8f5e9;">
                    <!-- Tabel Data Emisi Karbon -->
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark" style="background-color: #388e3c; color: white;">
                            <tr>
                                <th>#</th>
                                <th>Kode Emisi Karbon</th>
                                <th>Kategori Emisi Karbon</th>
                                <th>Tanggal Emisi</th>
                                <th>Kadar Emisi Karbon</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($emisis as $emisi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $emisi->kode_emisi_karbon }}</td>
                                <td>{{ $emisi->kategori_emisi_karbon }}</td>
                                <td>{{ $emisi->tanggal_emisi }}</td>
                                <td>{{ $emisi->kadar_emisi_karbon }}</td>
                                <td class="text-truncate" style="max-width: 200px; background-color: #f1f8e9;">
                                    {{ $emisi->deskripsi }}
                                </td>
                                <td>{{ $emisi->status }}</td>
                                <td>
                                    <a href="{{ route('emisicarbon.edit', $emisi->id) }}" class="btn btn-sm" style="background-color: #66bb6a; color: white; border-radius: 5px;">Edit</a>
                                    <form action="{{ route('emisicarbon.destroy', $emisi->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: #d32f2f; color: white; border-radius: 5px;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
