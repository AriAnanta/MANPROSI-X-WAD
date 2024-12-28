@extends('layouts.user')

@section('content')
<div class="container mt-6">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <form action="{{ route('emisicarbon.destroy', $emisiCarbon->kode_emisi_karbon) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        
                        <div class="card-body text-center">
                            <h4 class="mb-4 text-danger">Hapus Data Emisi Karbon</h4>
                            <p>Apakah Anda yakin ingin menghapus data dengan kode emisi karbon:</p>
                            <p class="font-weight-bold">{{ $emisiCarbon->kode_emisi_karbon }}</p>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger px-4">Hapus</button>
                                <a href="{{ route('emisicarbon.index') }}" class="btn btn-secondary px-4">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
