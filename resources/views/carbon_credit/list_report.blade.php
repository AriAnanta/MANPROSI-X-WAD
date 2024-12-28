@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Daftar Pembelian Carbon Credit untuk Laporan</h5>
                    <button type="button" id="printSelected" class="btn btn-light btn-sm" disabled>
                        <i class="fas fa-print"></i> Cetak Terpilih
                    </button>
                </div>

                <div class="card-body">
                    <!-- Filter Bulan -->
                    <div class="mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Filter berdasarkan Bulan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="monthFilter" class="form-label">Pilih Bulan</label>
                                        <select id="monthFilter" class="form-select shadow-sm border-success">
                                            <option value="">Semua Bulan</option>
                                            <option value="01">Januari</option>
                                            <option value="02">Februari</option>
                                            <option value="03">Maret</option>
                                            <option value="04">April</option>
                                            <option value="05">Mei</option>
                                            <option value="06">Juni</option>
                                            <option value="07">Juli</option>
                                            <option value="08">Agustus</option>
                                            <option value="09">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="yearFilter" class="form-label">Pilih Tahun</label>
                                        <select id="yearFilter" class="form-select shadow-sm border-success">
                                            <option value="">Semua Tahun</option>
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = 2020; // atau tahun awal yang Anda inginkan
                                            @endphp
                                            @for($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <form id="printForm" action="{{ route('carbon_credit.report') }}" method="GET" target="_blank">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th width="40px">
                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                        </th>
                                        <th>Kode Pembelian</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah (kg CO2)</th>
                                        <th>Deskripsi</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($carbon_credits as $credit)
                                        <tr class="credit-row" 
                                            data-date="{{ $credit->tanggal_pembelian_carbon_credit }}"
                                            data-month="{{ date('m', strtotime($credit->tanggal_pembelian_carbon_credit)) }}"
                                            data-year="{{ date('Y', strtotime($credit->tanggal_pembelian_carbon_credit)) }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input credit-checkbox" 
                                                       name="selected_credit[]" 
                                                       value="{{ $credit->kode_pembelian_carbon_credit }}">
                                            </td>
                                            <td>{{ $credit->kode_pembelian_carbon_credit }}</td>
                                            <td>{{ date('d/m/Y', strtotime($credit->tanggal_pembelian_carbon_credit)) }}</td>
                                            <td class="text-end">{{ number_format($credit->jumlah_kompensasi, 2) }}</td>
                                            <td>{{ $credit->deskripsi }}</td>
                                            <td class="text-center">
                                                @if($credit->bukti_pembelian)
                                                    <a href="{{ Storage::url($credit->bukti_pembelian) }}" 
                                                       class="btn btn-info btn-sm" 
                                                       target="_blank">
                                                        <i class="fas fa-file-alt"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary">Tidak ada bukti</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data pembelian carbon credit</td>
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
    .form-control {
        height: 38px; 
        font-size: 0.875rem; 
        padding: 0.25rem 0.5rem; 
        border-radius: 5px; 
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .form-control:hover {
        border-color: #6c757d; 
        box-shadow: 0 0 4px rgba(108, 117, 125, 0.3);
    }
    .form-control:focus {
        outline: none;
        border-color: #495057; 
        box-shadow: 0 0 6px rgba(73, 80, 87, 0.4);
    }
    .card-header.bg-light {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-header h6 {
        color: #495057;
        font-weight: 600;
    }
    
    input[type="month"] {
        height: 38px;
    }
    .form-select {
        height: 38px;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .form-select:hover {
        border-color: #6c757d;
        box-shadow: 0 0 4px rgba(108, 117, 125, 0.3);
    }
    .form-select:focus {
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
    const creditCheckboxes = document.querySelectorAll('.credit-checkbox');
    const printButton = document.getElementById('printSelected');
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    const printForm = document.getElementById('printForm');

    // Handle "Check All" functionality
    checkAll.addEventListener('change', function() {
        const visibleCheckboxes = document.querySelectorAll('.credit-row:not([style*="display: none"]) .credit-checkbox');
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updatePrintButton();
    });

    // Handle individual checkbox changes
    creditCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePrintButton);
    });

    // Handle filters
    function filterRows() {
        const selectedMonth = monthFilter.value;
        const selectedYear = yearFilter.value;
        const rows = document.querySelectorAll('.credit-row');
        
        rows.forEach(row => {
            const rowMonth = row.dataset.month;
            const rowYear = row.dataset.year;
            let showRow = true;

            if (selectedMonth && selectedYear) {
                showRow = rowMonth === selectedMonth && rowYear === selectedYear;
            } else if (selectedMonth) {
                showRow = rowMonth === selectedMonth;
            } else if (selectedYear) {
                showRow = rowYear === selectedYear;
            }

            row.style.display = showRow ? '' : 'none';
        });

        // Reset checkboxes
        checkAll.checked = false;
        creditCheckboxes.forEach(checkbox => checkbox.checked = false);
        updatePrintButton();
    }

    monthFilter.addEventListener('change', filterRows);
    yearFilter.addEventListener('change', filterRows);

    // Handle print button
    printButton.addEventListener('click', function() {
        // Add filters to form if selected
        if (monthFilter.value) {
            const monthInput = document.createElement('input');
            monthInput.type = 'hidden';
            monthInput.name = 'month';
            monthInput.value = monthFilter.value;
            printForm.appendChild(monthInput);
        }
        if (yearFilter.value) {
            const yearInput = document.createElement('input');
            yearInput.type = 'hidden';
            yearInput.name = 'year';
            yearInput.value = yearFilter.value;
            printForm.appendChild(yearInput);
        }

        printForm.submit();
    });

    function updatePrintButton() {
        const checkedBoxes = document.querySelectorAll('.credit-checkbox:checked');
        printButton.disabled = checkedBoxes.length === 0;
    }
});
</script>
@endpush
@endsection 