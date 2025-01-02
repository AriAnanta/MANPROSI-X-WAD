@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Daftar Emisi Karbon untuk Laporan</h5>
                    <button type="button" id="printSelected" class="btn btn-light btn-sm" disabled>
                        <i class="fas fa-print"></i> Cetak Terpilih
                    </button>
                </div>

                <div class="card-body">
                    <!-- Filter Container -->
                    <div class="mb-4 row">
                        <div class="col-md-4">
                            <label for="statusFilter" class="form-label">Filter Status</label>
                            <select id="statusFilter" class="form-select shadow-sm border-success">
                                <option value="all">Semua Status</option>
                                <option value="approved">Disetujui</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="monthFilter" class="form-label">Filter Bulan</label>
                            <input type="month" id="monthFilter" class="form-control shadow-sm border-success" 
                                   value="{{ date('Y-m') }}">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form id="printForm" action="{{ route('admin.emissions.selected.report') }}" method="GET" target="_blank">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th style="width: 50px">
                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                        </th>
                                        <th style="width: 150px">Pengguna</th>
                                        <th style="width: 100px">Tanggal</th>
                                        <th style="width: 120px">Kategori</th>
                                        <th style="width: 150px">Sub Kategori</th>
                                        <th style="width: 120px">Nilai Aktivitas</th>
                                        <th style="width: 150px">Kadar Emisi (kg CO₂)</th>
                                        <th style="width: 100px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emisiCarbons as $emisi)
                                        <tr class="emisi-row" 
                                            data-status="{{ $emisi->status }}"
                                            data-date="{{ date('Y-m', strtotime($emisi->tanggal_emisi)) }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input emisi-checkbox" 
                                                       name="selected_emisi[]" 
                                                       value="{{ $emisi->kode_emisi_karbon }}">
                                            </td>
                                            <td class="text-truncate position-relative" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="{{ $emisi->nama_user }}">
                                                {{ $emisi->nama_user }}
                                            </td>
                                            <td class="text-center text-nowrap">{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                                            <td class="text-nowrap">{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                                            <td class="text-truncate position-relative" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="{{ ucfirst($emisi->sub_kategori) }}">
                                                {{ ucfirst($emisi->sub_kategori) }}
                                            </td>
                                            <td>
                                                {{ number_format($emisi->nilai_aktivitas, 2) }} {{ $emisi->satuan ?? '-' }}
                                            </td>
                                            <td class="text-end">{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $emisi->status === 'approved' ? 'success' : ($emisi->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($emisi->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data emisi karbon</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const emisiCheckboxes = document.querySelectorAll('.emisi-checkbox');
    const printButton = document.getElementById('printSelected');
    const statusFilter = document.getElementById('statusFilter');
    const monthFilter = document.getElementById('monthFilter');
    const printForm = document.getElementById('printForm');

    function applyFilters() {
        const selectedStatus = statusFilter.value;
        const selectedMonth = monthFilter.value;
        const rows = document.querySelectorAll('.emisi-row');

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowDate = row.dataset.date;
            
            const statusMatch = selectedStatus === 'all' || rowStatus === selectedStatus;
            const monthMatch = !selectedMonth || rowDate === selectedMonth;

            row.style.display = (statusMatch && monthMatch) ? '' : 'none';
        });

        // Reset checkboxes after filtering
        checkAll.checked = false;
        emisiCheckboxes.forEach(checkbox => checkbox.checked = false);
        updatePrintButton();
    }

    // Handle status filter change
    statusFilter.addEventListener('change', applyFilters);

    // Handle month filter change
    monthFilter.addEventListener('change', applyFilters);

    // Handle "Check All" functionality
    checkAll.addEventListener('change', function() {
        const visibleCheckboxes = document.querySelectorAll('.emisi-row:not([style*="display: none"]) .emisi-checkbox');
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updatePrintButton();
    });

    // Handle individual checkbox changes
    emisiCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePrintButton);
    });

    // Handle print button
    printButton.addEventListener('click', function() {
        printForm.submit();
    });

    function updatePrintButton() {
        const checkedBoxes = document.querySelectorAll('.emisi-checkbox:checked');
        printButton.disabled = checkedBoxes.length === 0;
    }

    // Apply filters on page load
    applyFilters();
});
</script>
@endpush

@push('styles')
<style>
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

#statusFilter, #monthFilter {
    height: 38px;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 5px;
    transition: all 0.2s ease-in-out;
}

#statusFilter:focus, #monthFilter:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
}

.bg-gradient-success {
    background: linear-gradient(90deg, #28a745, #218838);
}

.btn-light {
    background-color: #fff;
    border: 1px solid #ddd;
    transition: all 0.2s ease-in-out;
}

.btn-light:hover:not(:disabled) {
    background-color: #f8f9fa;
    transform: translateY(-1px);
}

.btn-light:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endpush
@endsection 