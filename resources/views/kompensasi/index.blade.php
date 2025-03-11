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
                                    {{ number_format(collect($emisiApproved)->sum('emisi_ton'), 2) }}
                                    <small>ton CO₂e</small>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6>Total Terkompensasi</h6>
                                <h4 class="text-primary mb-0">
                                    {{ number_format(collect($riwayatKompensasi)->sum('jumlah_ton'), 2) }}
                                    <small>ton CO₂e</small>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 text-center">
                                <h6>Sisa Emisi</h6>
                                <h4 class="text-warning mb-0">
                                    {{ number_format(collect($emisiApproved)->sum('sisa_emisi_ton'), 2) }}
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
                                    <th style="width: 200px">Kategori</th>
                                    <th style="width: 150px">Total Emisi</th>
                                    <th style="width: 150px">Terkompensasi</th>
                                    <th style="width: 150px">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategoriEmisi as $data)
                                    <tr>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ ucfirst($data['kategori']) }}">
                                            {{ ucfirst($data['kategori']) }}
                                        </td>
                                        <td class="text-end text-nowrap">{{ number_format($data['total'], 2) }} ton CO₂e</td>
                                        <td class="text-end text-nowrap">{{ number_format($data['terkompensasi'], 2) }} ton CO₂e</td>
                                        <td class="text-end text-nowrap">{{ number_format($data['sisa'], 2) }} ton CO₂e</td>
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
                                                    (Sisa: {{ number_format($emisi->sisa_emisi_ton, 4) }} ton CO₂e)
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
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Riwayat Kompensasi</h5>
                    <div>
                        <a href="{{ route('manager.kompensasi.report') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <button class="btn btn-light btn-sm" type="button" onclick="toggleFilter()">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <div class="card-body">
                <!-- Filter Form - Hidden by default -->
                    <form method="GET" action="{{ route('manager.kompensasi.index') }}" class="row g-3 mb-3" id="filterForm" style="display: none;">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                placeholder="Search kode/kategori...">
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>

                        <!-- Status -->
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-2">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori">
                                <option value="">All</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->kategori_emisi_karbon }}" 
                                        {{ request('kategori') == $kategori->kategori_emisi_karbon ? 'selected' : '' }}>
                                        {{ $kategori->kategori_emisi_karbon }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        

                        <!-- Filter Button -->
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                <a href="{{ route('manager.kompensasi.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Active Filters Display -->
                    @if(request()->anyFilled(['search', 'start_date', 'end_date', 'status', 'kategori']))
                        <div class="mt-3 p-2 bg-light rounded">
                            <strong>Active Filters:</strong>
                            @if(request('search'))
                                <span class="badge bg-info me-2">Search: {{ request('search') }}</span>
                            @endif
                            @if(request('start_date'))
                                <span class="badge bg-info me-2">From: {{ request('start_date') }}</span>
                            @endif
                            @if(request('end_date'))
                                <span class="badge bg-info me-2">To: {{ request('end_date') }}</span>
                            @endif
                            @if(request('status'))
                                <span class="badge bg-info me-2">Status: {{ ucfirst(request('status')) }}</span>
                            @endif
                            @if(request('kategori'))
                                <span class="badge bg-info me-2">Kategori: {{ request('kategori') }}</span>
                            @endif
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th style="width: 40px">No</th>
                                    <th style="width: 120px">Kode Kompensasi</th>
                                    <th style="width: 120px">Kode Emisi</th>
                                    <th style="width: 100px">Kategori</th>
                                    <th style="width: 150px">Sub Kategori</th>
                                    <th style="width: 120px">Jumlah (ton CO₂e)</th>
                                    <th style="width: 90px">Tanggal</th>
                                    <th style="width: 100px">Status</th>
                                    <th style="width: 120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatKompensasi as $kompensasi)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-nowrap">{{ $kompensasi->kode_kompensasi }}</td>
                                        <td class="text-nowrap">{{ $kompensasi->kode_emisi_karbon }}</td>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ ucfirst($kompensasi->kategori_emisi) }}">
                                            {{ ucfirst($kompensasi->kategori_emisi) }}
                                        </td>
                                        <td class="text-truncate position-relative" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ ucfirst($kompensasi->sub_kategori) }}">
                                            {{ ucfirst($kompensasi->sub_kategori) }}
                                        </td>
                                        <td class="text-end text-nowrap">{{ number_format($kompensasi->jumlah_ton, 4) }}</td>
                                        <td class="text-center text-nowrap">{{ date('d/m/Y', strtotime($kompensasi->tanggal_kompensasi)) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $kompensasi->status === 'approved' || $kompensasi->status === 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($kompensasi->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('manager.kompensasi.show', ['kompensasi' => $kompensasi->kode_kompensasi]) }}" 
                                                   class="btn btn-info btn-sm" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($kompensasi->status === 'pending')
                                                    <a href="{{ route('manager.kompensasi.edit', ['kompensasi' => $kompensasi->kode_kompensasi]) }}" 
                                                       class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('manager.kompensasi.destroy', ['kompensasi' => $kompensasi->kode_kompensasi]) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
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
.badge {
    font-size: 0.875em;
    padding: 0.5em 0.75em;
}
.me-2 {
    margin-right: 0.5rem;
}
/* Animation for filter form */
#filterForm {
    transition: all 0.3s ease-in-out;
}
/* Table styles */
.table {
    table-layout: fixed;
    width: 100%;
}

.table td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Tooltip styles */
.tooltip-large .tooltip-inner {
    max-width: 400px;
    padding: 10px 15px;
    background-color: rgba(0, 0, 0, 0.9);
    font-size: 14px;
    line-height: 1.4;
    text-align: left;
    word-break: break-word;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

[data-bs-toggle="tooltip"] {
    cursor: help;
}

/* Responsive styles */
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        min-width: 1200px;
    }
    .tooltip-large .tooltip-inner {
        max-width: 300px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function toggleFilter() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm.style.display === 'none') {
        filterForm.style.display = 'flex';
    } else {
        filterForm.style.display = 'none';
    }
}

// Show filter form (kalau nyala)
document.addEventListener('DOMContentLoaded', function() {
    const hasFilters = {{ request()->anyFilled(['search', 'start_date', 'end_date', 'status', 'kategori']) ? 'true' : 'false' }};
    if (hasFilters) {
        document.getElementById('filterForm').style.display = 'flex';
    }
});


</script>
@endpush
@endsection 