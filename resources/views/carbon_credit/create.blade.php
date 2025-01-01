@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Input Pembelian Carbon Credit</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('carbon_credit.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Input Kompensasi -->
                        <div class="mb-4">
                            <label for="kode_kompensasi" class="form-label">Pilih Kompensasi</label>
                            <select class="form-select @error('kode_kompensasi') is-invalid @enderror" 
                                    id="kode_kompensasi" 
                                    name="kode_kompensasi" 
                                    required>
                                <option value="">Pilih Kompensasi</option>
                                @foreach($kompensasiPending as $kompensasi)
                                    <option value="{{ $kompensasi->kode_kompensasi }}" 
                                            data-jumlah="{{ $kompensasi->jumlah_kompensasi }}">
                                        {{ $kompensasi->kode_kompensasi }} - 
                                        {{ number_format($kompensasi->jumlah_kompensasi / 1000, 3) }} ton CO₂e
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_kompensasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Setelah input kode_kompensasi -->
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
                                            data-mata-uang="{{ $penyedia->mata_uang }}">
                                        {{ $penyedia->nama_penyedia }} - 
                                        {{ number_format($penyedia->harga_per_ton, 2) }} {{ $penyedia->mata_uang }}/ton CO₂
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_penyedia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Jumlah Emisi yang Dikompensasi</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="jumlah_display" readonly>
                                <span class="input-group-text">kg CO₂e</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Total Harga</label>
                            <div class="input-group">
                                <span class="input-group-text" id="mata-uang-display">IDR</span>
                                <input type="text" class="form-control" id="total_harga_display" readonly>
                            </div>
                            <small class="text-muted mt-1" id="harga_per_ton_display"></small>
                        </div>

                        <!-- Hidden inputs untuk form submission -->
                        <input type="hidden" name="jumlah_kompensasi" id="jumlah_kompensasi">
                        <input type="hidden" name="harga_per_ton" id="harga_per_ton">
                        <input type="hidden" name="total_harga" id="total_harga">

                        <!-- Input Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal_pembelian_carbon_credit" class="form-label">Tanggal Pembelian</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_pembelian_carbon_credit') is-invalid @enderror" 
                                   id="tanggal_pembelian_carbon_credit" 
                                   name="tanggal_pembelian_carbon_credit" 
                                   value="{{ old('tanggal_pembelian_carbon_credit') }}" 
                                   required>
                            @error('tanggal_pembelian_carbon_credit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Bukti Pembelian -->
                        <div class="mb-4">
                            <label for="bukti_pembelian" class="form-label">Bukti Pembelian</label>
                            <input type="file" 
                                   class="form-control @error('bukti_pembelian') is-invalid @enderror" 
                                   id="bukti_pembelian" 
                                   name="bukti_pembelian" 
                                   accept=".pdf,.jpg,.jpeg,.png" 
                                   required>
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
                                      required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-5">Simpan</button>
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
    const kompensasiSelect = document.getElementById('kode_kompensasi');
    const penyediaSelect = document.getElementById('kode_penyedia');
    const jumlahInput = document.getElementById('jumlah_kompensasi');
    const jumlahDisplay = document.getElementById('jumlah_display');
    const hargaPerTonInput = document.getElementById('harga_per_ton');
    const totalHargaInput = document.getElementById('total_harga');
    const totalHargaDisplay = document.getElementById('total_harga_display');
    const mataUangDisplay = document.getElementById('mata-uang-display');
    const hargaPerTonDisplay = document.getElementById('harga_per_ton_display');

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
        
        // Update displays
        jumlahDisplay.value = formatNumber(jumlahKg);
        totalHargaInput.value = total.toFixed(2);
        totalHargaDisplay.value = formatNumber(total);
        
        // Tampilkan informasi harga per ton
        hargaPerTonDisplay.textContent = `Harga: ${formatNumber(hargaPerTon)} ${mataUang}/ton CO₂`;
    }

    kompensasiSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const jumlah = selectedOption.dataset.jumlah || '0';
        jumlahInput.value = jumlah;
        hitungTotalHarga();
    });

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