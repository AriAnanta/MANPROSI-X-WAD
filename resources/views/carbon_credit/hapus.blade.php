@extends('layouts.admin')

@section('content')
<div class="container mt-6">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h4 class="mb-4 text-danger">Hapus Data Pembelian Carbon Credit</h4>
                    <p>Apakah Anda yakin ingin menghapus data dengan kode pembelian carbon credit <strong>{{ $pembelianCarbonCredit->kode_pembelian_carbon_credit }}</strong>?</p>
                        
                    <form action="{{ route('carbon_credit.destroy', $pembelianCarbonCredit->kode_pembelian_carbon_credit) }}" method="POST">
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
