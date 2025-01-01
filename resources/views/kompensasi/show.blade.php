@extends('layouts.manager')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Kompensasi</h5>
                    <a href="{{ route('manager.kompensasi.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Informasi Kompensasi</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200">Kode Kompensasi</td>
                                    <td>: {{ $kompensasi->kode_kompensasi }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah Kompensasi</td>
                                    <td>: {{ number_format($kompensasi->jumlah_ton, 2) }} ton CO₂e</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Kompensasi</td>
                                    <td>: {{ \Carbon\Carbon::parse($kompensasi->tanggal_kompensasi)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>: <span class="badge bg-{{ 
                                        $kompensasi->status === 'pending' ? 'warning' : 
                                        ($kompensasi->status === 'approved' ? 'success' : 'danger') 
                                    }}">{{ ucfirst($kompensasi->status) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>: {{ \Carbon\Carbon::parse($kompensasi->created_at)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td>Updated At</td>
                                    <td>: {{ \Carbon\Carbon::parse($kompensasi->updated_at)->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Informasi Emisi Karbon</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200">Kode Emisi</td>
                                    <td>: {{ $kompensasi->kode_emisi_karbon }}</td>
                                </tr>
                                <tr>
                                    <td>Kategori</td>
                                    <td>: {{ ucfirst($kompensasi->kategori_emisi) }}</td>
                                </tr>
                                <tr>
                                    <td>Sub Kategori</td>
                                    <td>: {{ ucfirst($kompensasi->sub_kategori) }}</td>
                                </tr>
                                <tr>
                                    <td>Kadar Emisi</td>
                                    <td>: {{ number_format($kompensasi->kadar_emisi_ton, 2) }} ton CO₂e</td>
                                </tr>
                                <tr>
                                    <td>Nilai Aktivitas</td>
                                    <td>: {{ $kompensasi->nilai_aktivitas }}</td>
                                </tr>
                                <tr>
                                    <td>Faktor Emisi</td>
                                    <td>: {{ $kompensasi->faktor_emisi }}</td>
                                </tr>
                                <tr>
                                    <td>Deskripsi</td>
                                    <td>: {{ $kompensasi->deskripsi }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 