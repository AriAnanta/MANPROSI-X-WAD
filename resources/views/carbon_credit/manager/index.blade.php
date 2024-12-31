@extends('layouts.manager')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Ringkasan Pembelian Carbon Credit</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pembelian</h5>
                                    <p class="card-text h3">{{ $summary['total_pembelian'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Kompensasi</h5>
                                    <p class="card-text h3">{{ number_format($summary['total_kompensasi'], 3) }} ton</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Kompensasi Selesai</h5>
                                    <p class="card-text h3">{{ $summary['completed_kompensasi'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Kompensasi Pending</h5>
                                    <p class="card-text h3">{{ $summary['pending_kompensasi'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header pb-0">
                    <h6>Daftar Pembelian Carbon Credit</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Kode Pembelian</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carbonCredits as $credit)
                                <tr>
                                    <td>{{ $credit['kode_pembelian'] }}</td>
                                    <td>{{ $credit['kategori'] }}</td>
                                    <td>{{ $credit['sub_kategori'] }}</td>
                                    <td>{{ $credit['jumlah_kompensasi'] }} ton</td>
                                    <td>{{ $credit['tanggal_pembelian'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $credit['status_kompensasi'] === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($credit['status_kompensasi']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailModal{{ $credit['kode_pembelian'] }}">
                                            Detail
                                        </button>
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

<!-- Detail Modals -->
@foreach($carbonCredits as $credit)
<div class="modal fade" id="detailModal{{ $credit['kode_pembelian'] }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembelian Carbon Credit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Kode Pembelian:</strong> {{ $credit['kode_pembelian'] }}
                </div>
                <div class="mb-3">
                    <strong>Kode Kompensasi:</strong> {{ $credit['kode_kompensasi'] }}
                </div>
                <div class="mb-3">
                    <strong>Kategori:</strong> {{ $credit['kategori'] }}
                </div>
                <div class="mb-3">
                    <strong>Sub Kategori:</strong> {{ $credit['sub_kategori'] }}
                </div>
                <div class="mb-3">
                    <strong>Jumlah Kompensasi:</strong> {{ $credit['jumlah_kompensasi'] }} ton
                </div>
                <div class="mb-3">
                    <strong>Tanggal Pembelian:</strong> {{ $credit['tanggal_pembelian'] }}
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $credit['status_kompensasi'] === 'completed' ? 'success' : 'warning' }}">
                        {{ ucfirst($credit['status_kompensasi']) }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Deskripsi:</strong><br>
                    {{ $credit['deskripsi'] }}
                </div>
                @if($credit['bukti_pembelian'])
                <div class="mb-3">
                    <strong>Bukti Pembelian:</strong><br>
                    <a href="{{ Storage::url($credit['bukti_pembelian']) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-primary">
                        Lihat Bukti
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection 