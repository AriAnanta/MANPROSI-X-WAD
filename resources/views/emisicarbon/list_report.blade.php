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
                            <select id="statusFilter" class="form-select shadow-sm border-success">
                                <option value="all">Semua Status</option>
                                <option value="approved">Disetujui</option>
                                <option value="pending">Pending</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="month" id="monthFilter" class="form-control shadow-sm border-success" 
                                   value="{{ date('Y-m') }}">
                        </div>
                    </div>
                    

                    <div class="table-responsive">
                        <form id="printForm" action="{{ route('admin.emissions.report') }}" method="GET" target="_blank">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th width="40px">
                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                        </th>
                                        <th>Pengguna</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Kadar Emisi (kg CO2)</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emisiCarbons as $emisi)
                                        <tr class="emisi-row" data-status="{{ $emisi->status }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input emisi-checkbox" 
                                                       name="selected_emisi[]" 
                                                       value="{{ $emisi->kode_emisi_karbon }}">
                                            </td>
                                            <td>{{ $emisi->nama_user }}</td>
                                            <td>{{ date('d/m/Y', strtotime($emisi->tanggal_emisi)) }}</td>
                                            <td>{{ ucfirst($emisi->kategori_emisi_karbon) }}</td>
                                            <td class="text-end">{{ number_format($emisi->kadar_emisi_karbon, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $emisi->status === 'approved' ? 'success' : ($emisi->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($emisi->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $emisi->deskripsi }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data emisi karbon</td>
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

@push('styles')
<style>
    .btn-group .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
    .status-filter.active {
        background-color: #198754;
        color: white;
    }
    #statusFilter {
    height: 38px; 
    font-size: 0.875rem; 
    padding: 0.25rem 0.5rem; 
    border-radius: 5px; 
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

#statusFilter:hover {
    border-color: #6c757d; 
    box-shadow: 0 0 4px rgba(108, 117, 125, 0.3);
}

#statusFilter:focus {
    outline: none;
    border-color: #495057; 
    box-shadow: 0 0 6px rgba(73, 80, 87, 0.4);
}

#monthFilter {
    height: 38px;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

#monthFilter:hover {
    border-color: #6c757d;
    box-shadow: 0 0 4px rgba(108, 117, 125, 0.3);
}

#monthFilter:focus {
    outline: none;
    border-color: #495057;
    box-shadow: 0 0 6px rgba(73, 80, 87, 0.4);
}

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const emisiCheckboxes = document.querySelectorAll('.emisi-checkbox');
    const printButton = document.getElementById('printSelected');
    const statusFilter = document.getElementById('statusFilter');
    const printForm = document.getElementById('printForm');
    const monthFilter = document.getElementById('monthFilter');

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

    // Handle month filter
    monthFilter.addEventListener('change', function() {
        const selectedMonth = this.value;
        const rows = document.querySelectorAll('.emisi-row');
        
        rows.forEach(row => {
            const dateCell = row.querySelector('td:nth-child(3)').textContent;
            const rowDate = convertDateToYearMonth(dateCell);
            
            const statusMatch = statusFilter.value === 'all' || 
                              row.dataset.status === statusFilter.value;
            const monthMatch = selectedMonth === '' || 
                             rowDate === selectedMonth;

            row.style.display = (statusMatch && monthMatch) ? '' : 'none';
        });

        // Reset checkboxes
        checkAll.checked = false;
        emisiCheckboxes.forEach(checkbox => checkbox.checked = false);
        updatePrintButton();
    });

    // Fungsi untuk mengkonversi format tanggal dd/mm/yyyy ke yyyy-mm
    function convertDateToYearMonth(dateStr) {
        const [day, month, year] = dateStr.split('/');
        return `${year}-${month.padStart(2, '0')}`;
    }

    // Handle status filter
    statusFilter.addEventListener('change', function() {
        const status = this.value;
        const selectedMonth = monthFilter.value;
        const rows = document.querySelectorAll('.emisi-row');
        
        rows.forEach(row => {
            const dateCell = row.querySelector('td:nth-child(3)').textContent;
            const rowDate = convertDateToYearMonth(dateCell);
            
            const statusMatch = status === 'all' || row.dataset.status === status;
            const monthMatch = selectedMonth === '' || 
                             rowDate === selectedMonth;

            row.style.display = (statusMatch && monthMatch) ? '' : 'none';
        });

        // Reset checkboxes
        checkAll.checked = false;
        emisiCheckboxes.forEach(checkbox => checkbox.checked = false);
        updatePrintButton();
    });

    // Handle print button
    printButton.addEventListener('click', function() {
        printForm.submit();
    });

    function updatePrintButton() {
        const checkedBoxes = document.querySelectorAll('.emisi-checkbox:checked');
        printButton.disabled = checkedBoxes.length === 0;
    }
});
</script>
@endpush
@endsection 