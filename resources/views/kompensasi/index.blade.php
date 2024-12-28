@extends('layouts.manager')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Card Ringkasan Emisi -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Ringkasan Emisi Carbon</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6>Total Emisi (Approved)</h6>
                                <h4 class="text-success mb-0">
                                    {{ number_format(collect($emisiApproved)->sum('emisi_ton'), 3) }}
                                    <small>ton CO₂e</small>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6>Total Terkompensasi</h6>
                                <h4 class="text-primary mb-0">
                                    {{ number_format(collect($riwayatKompensasi)->sum('jumlah_ton'), 3) }}
                                    <small>ton CO₂e</small>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6>Sisa Emisi</h6>
                                <h4 class="text-warning mb-0">
                                    {{ number_format(collect($emisiApproved)->sum('sisa_emisi_ton'), 3) }}
                                    <small>ton CO₂e</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Detail Emisi per Kategori -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Detail Emisi per Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-warning">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Total Emisi</th>
                                    <th>Terkompensasi</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategoriEmisi as $data)
                                    <tr>
                                        <td>{{ ucfirst($data['kategori']) }}</td>
                                        <td>{{ number_format($data['total'], 3) }} ton CO₂e</td>
                                        <td>{{ number_format($data['terkompensasi'], 3) }} ton CO₂e</td>
                                        <td>{{ number_format($data['sisa'], 3) }} ton CO₂e</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card Form Kompensasi -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Form Kompensasi Emisi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('manager.kompensasi.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Pilih Emisi</label>
                                    <select name="kode_emisi_karbon" class="form-select" required>
                                        <option value="">Pilih Emisi</option>
                                        @foreach($emisiApproved as $emisi)
                                            @if($emisi->sisa_emisi_ton > 0)
                                                <option value="{{ $emisi->kode_emisi_karbon }}">
                                                    {{ $emisi->kategori_emisi_karbon }} - 
                                                    {{ $emisi->sub_kategori }} 
                                                    (Sisa: {{ number_format($emisi->sisa_emisi_ton, 3) }} ton CO₂e)
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Jumlah Kompensasi</label>
                                    <div class="input-group">
                                        <input type="number" name="jumlah_kompensasi" 
                                               class="form-control" step="0.001" min="0.001" required>
                                        <span class="input-group-text">ton CO₂e</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-success w-100">
                                        Kompensasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Riwayat Kompensasi -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Riwayat Kompensasi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Kode Kompensasi</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatKompensasi as $kompensasi)
                                    <tr>
                                        <td>{{ $kompensasi['kode_kompensasi'] }}</td>
                                        <td>{{ ucfirst($kompensasi['kategori_emisi']) }}</td>
                                        <td>{{ ucfirst($kompensasi['sub_kategori']) }}</td>
                                        <td>{{ number_format($kompensasi['jumlah_ton'], 3) }} ton CO₂e</td>
                                        <td>{{ $kompensasi['tanggal_kompensasi']->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $kompensasi['status'] === 'pending' ? 'warning' : 
                                                ($kompensasi['status'] === 'completed' ? 'success' : 'danger') 
                                            }}">
                                                {{ ucfirst($kompensasi['status']) }}
                                            </span>
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
</div>

@push('styles')
<style>
.border {
    border-color: #dee2e6 !important;
}
.rounded {
    border-radius: 0.5rem !important;
}
.text-success { color: #28a745 !important; }
.text-primary { color: #007bff !important; }
.text-warning { color: #ffc107 !important; }
h4 small {
    font-size: 0.875rem;
    opacity: 0.8;
}
</style>
@endpush
@endsection 