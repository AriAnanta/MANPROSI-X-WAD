@extends('layouts.app')

@section('content')
<div class="container mt-6">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4 class="mb-4 text-danger">Hapus Data Emisi Karbon</h4>
                    <p>Apakah Anda yakin ingin menghapus data dengan kode emisi karbon <strong>{{ $emisi->kode_emisi_karbon }}</strong>?</p>
                    
                    <form action="{{ route('emisicarbon.destroy', $emisiCarbon->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <a href="{{ route('emisicarbon.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
