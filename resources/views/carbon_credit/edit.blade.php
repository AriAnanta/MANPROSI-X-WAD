@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Edit Pembelian Carbon Credit</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('carbon_credit.update', $carbon_credit->kode_pembelian_carbon_credit) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Setelah input kompensasi -->
                        <div class="mb-4">
                            <label for="kode_penyedia" class="form-label">Pilih Penyedia Carbon Credit</label>
                            <select class="form-select @error('kode_penyedia') is-invalid @enderror" 
                                    id="kode_penyedia" 
                                    name="kode_penyedia" 
                                    required>
                                <option value="">Pilih Penyedia</option>
                                @foreach($penyediaList as $penyedia)
                                    <option value="{{ $penyedia->kode_penyedia }}" 
                                            data-harga="{{ $penyedia->harga_per_ton }}"
                                            data-mata-uang="{{ $penyedia->mata_uang }}"
                                            {{ $carbon_credit->kode_penyedia == $penyedia->kode_penyedia ? 'selected' : '' }}>
                                        {{ $penyedia->nama_penyedia }} - 
                                        {{ number_format($penyedia->harga_per_ton, 2) }} {{ $penyedia->mata_uang }}/ton CO₂
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_penyedia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal_pembelian_carbon_credit" class="form-label">Tanggal Pembelian</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_pembelian_carbon_credit') is-invalid @enderror" 
                                   id="tanggal_pembelian_carbon_credit" 
                                   name="tanggal_pembelian_carbon_credit" 
                                   value="{{ old('tanggal_pembelian_carbon_credit', $carbon_credit->tanggal_pembelian_carbon_credit) }}" 
                                   required>
                            @error('tanggal_pembelian_carbon_credit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Jumlah -->
                        <div class="mb-4">
                            <label for="jumlah_kompensasi" class="form-label">Jumlah (kg CO₂)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('jumlah_kompensasi') is-invalid @enderror" 
                                   id="jumlah_kompensasi" 
                                   name="jumlah_kompensasi" 
                                   value="{{ old('jumlah_kompensasi', $carbon_credit->jumlah_kompensasi) }}" 
                                   required>
                            @error('jumlah_kompensasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Bukti Pembelian -->
                        <div class="mb-4">
                            <label for="bukti_pembelian" class="form-label">Bukti Pembelian</label>
                            @if($carbon_credit->bukti_pembelian)
                                <div class="mb-2">
                                    <a href="{{ Storage::url($carbon_credit->bukti_pembelian) }}" 
                                       class="btn btn-info btn-sm" 
                                       target="_blank">
                                        <i class="fas fa-file-alt"></i> Lihat Bukti Saat Ini
                                    </a>
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('bukti_pembelian') is-invalid @enderror" 
                                   id="bukti_pembelian" 
                                   name="bukti_pembelian" 
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max: 10MB)</small>
                            @error('bukti_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4" 
                                      required>{{ old('deskripsi', $carbon_credit->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tampilkan informasi harga -->
                        <div class="mb-4">
                            <label class="form-label">Jumlah Emisi yang Dikompensasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ number_format($carbon_credit->jumlah_kompensasi, 0) }}" readonly>
                                <span class="input-group-text">kg CO₂e</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ $carbon_credit->penyediaCarbonCredit->mata_uang }}</span>
                                <input type="text" class="form-control" value="{{ number_format($carbon_credit->total_harga, 2) }}" readonly>
                            </div>
                            <small class="text-muted mt-1">
                                Harga: {{ number_format($carbon_credit->harga_per_ton, 2) }} {{ $carbon_credit->penyediaCarbonCredit->mata_uang }}/ton CO₂
                            </small>
                        </div>

                        

                        <!-- Hidden inputs untuk perhitungan -->
                        <input type="hidden" name="jumlah_kompensasi" id="jumlah_kompensasi" value="{{ $carbon_credit->jumlah_kompensasi }}">
                        <input type="hidden" name="harga_per_ton" id="harga_per_ton" value="{{ $carbon_credit->harga_per_ton }}">
                        <input type="hidden" name="total_harga" id="total_harga" value="{{ $carbon_credit->total_harga }}">

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-5">Update</button>
                            <a href="{{ route('carbon_credit.index') }}" class="btn btn-outline-secondary px-5">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
    }
    .btn-success {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-success:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.5);
    }
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .bg-gradient-success {
        background: linear-gradient(90deg, #28a745, #218838);
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const penyediaSelect = document.getElementById('kode_penyedia');
    const jumlahInput = document.getElementById('jumlah_kompensasi');
    const hargaPerTonInput = document.getElementById('harga_per_ton');
    const totalHargaInput = document.getElementById('total_harga');
    const totalHargaDisplay = document.querySelector('input[value="{{ number_format($carbon_credit->total_harga, 2) }}"]');
    const mataUangDisplay = document.querySelector('.input-group-text');
    const hargaPerTonDisplay = document.querySelector('.text-muted.mt-1');

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function hitungTotalHarga() {
        const jumlahKg = parseFloat(jumlahInput.value) || 0;
        const hargaPerTon = parseFloat(hargaPerTonInput.value) || 0;
        const mataUang = mataUangDisplay.textContent;
        
        // Konversi kg ke ton (1 ton = 1000 kg)
        const jumlahTon = jumlahKg / 1000;
        const total = jumlahTon * hargaPerTon;
        
        totalHargaInput.value = total.toFixed(2);
        totalHargaDisplay.value = formatNumber(total);
        
        // Update informasi harga per ton
        hargaPerTonDisplay.textContent = `Harga: ${formatNumber(hargaPerTon)} ${mataUang}/ton CO₂`;
    }

    penyediaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const hargaPerTon = selectedOption.dataset.harga || '0';
        const mataUang = selectedOption.dataset.mataUang || 'IDR';
        
        hargaPerTonInput.value = hargaPerTon;
        mataUangDisplay.textContent = mataUang;
        hitungTotalHarga();
    });
});
</script>
@endpush
@endsection 