@extends('layouts.user')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0 text-center fw-semibold">Edit Data Emisi Karbon</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('emisicarbon.update', $emisiCarbon->kode_emisi_karbon) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Input Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal_emisi" class="form-label">Tanggal</label>
                            <input type="date" 
                                   class="form-control @error('tanggal_emisi') is-invalid @enderror" 
                                   id="tanggal_emisi" name="tanggal_emisi" 
                                   value="{{ old('tanggal_emisi', $emisiCarbon->tanggal_emisi) }}" required>
                            @error('tanggal_emisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Kategori -->
                        <div class="mb-4">
                            <label for="kategori_emisi_karbon" class="form-label">Kategori Emisi</label>
                            <select class="form-select @error('kategori_emisi_karbon') is-invalid @enderror" 
                                    id="kategori_emisi_karbon" name="kategori_emisi_karbon" required>
                                <option value="">Pilih Kategori Emisi</option>
                                @foreach($kategoriEmisi as $kategori => $faktorList)
                                    <option value="{{ $kategori }}" {{ old('kategori_emisi_karbon', $emisiCarbon->kategori_emisi_karbon) == $kategori ? 'selected' : '' }}>
                                        {{ ucfirst($kategori) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_emisi_karbon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Sub Kategori -->
                        <div class="mb-4">
                            <label for="sub_kategori" class="form-label">Sub Kategori</label>
                            <select class="form-select @error('sub_kategori') is-invalid @enderror" 
                                    id="sub_kategori" name="sub_kategori" required>
                                <option value="">Pilih Sub Kategori</option>
                            </select>
                            @error('sub_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Nilai Aktivitas -->
                        <div class="mb-4">
                            <label for="nilai_aktivitas" class="form-label">Nilai Aktivitas</label>
                            <div class="input-group">
                                <input type="number" step="0.01" 
                                       class="form-control @error('nilai_aktivitas') is-invalid @enderror" 
                                       id="nilai_aktivitas" name="nilai_aktivitas" 
                                       value="{{ old('nilai_aktivitas', $emisiCarbon->nilai_aktivitas) }}" required>
                                <span class="input-group-text" id="satuan-addon"></span>
                            </div>
                            @error('nilai_aktivitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" placeholder="Tambahkan deskripsi..." required>{{ old('deskripsi', $emisiCarbon->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-5">Update</button>
                            <a href="{{ route('emisicarbon.index') }}" class="btn btn-outline-secondary px-5">Kembali</a>
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

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
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
const kategoriSelect = document.getElementById('kategori_emisi_karbon');
const subKategoriSelect = document.getElementById('sub_kategori');
const satuanAddon = document.getElementById('satuan-addon');

// Data faktor emisi dari database
const faktorEmisiData = @json($kategoriEmisi);
const currentSubKategori = '{{ old('sub_kategori', $emisiCarbon->sub_kategori) }}';

function updateSubKategori() {
    const kategori = kategoriSelect.value;
    subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
    
    if (kategori && faktorEmisiData[kategori]) {
        faktorEmisiData[kategori].forEach(faktor => {
            const option = new Option(
                faktor.sub_kategori, 
                faktor.sub_kategori,
                false,
                faktor.sub_kategori === currentSubKategori
            );
            subKategoriSelect.add(option);
        });
        
        if (faktorEmisiData[kategori].length > 0) {
            satuanAddon.textContent = faktorEmisiData[kategori][0].satuan;
        }
    }
}

// Set initial values
updateSubKategori();

// Event listeners
kategoriSelect.addEventListener('change', updateSubKategori);

// Fungsi untuk menghitung emisi (sama seperti di create.blade.php)
function hitungEmisi() {
    const kategori = kategoriSelect.value;
    const subKategori = subKategoriSelect.value;
    const nilaiAktivitas = parseFloat(document.getElementById('nilai_aktivitas').value) || 0;
    
    if (kategori && subKategori && nilaiAktivitas > 0) {
        const faktor = faktorEmisiData[kategori].find(f => f.sub_kategori === subKategori);
        if (faktor) {
            const hasil = nilaiAktivitas * faktor.nilai_faktor;
            document.querySelector('.hasil-konversi').innerHTML = `
                <div class="alert alert-info">
                    <strong>Hasil Konversi:</strong><br>
                    ${nilaiAktivitas.toFixed(2)} ${faktor.satuan} × 
                    ${faktor.nilai_faktor} kg CO₂/satuan = 
                    ${hasil.toFixed(2)} kg CO₂e
                </div>
            `;
        }
    }
}

// Event listeners untuk perhitungan otomatis
kategoriSelect.addEventListener('change', hitungEmisi);
subKategoriSelect.addEventListener('change', hitungEmisi);
document.getElementById('nilai_aktivitas').addEventListener('input', hitungEmisi);
</script>
@endpush
@endsection
