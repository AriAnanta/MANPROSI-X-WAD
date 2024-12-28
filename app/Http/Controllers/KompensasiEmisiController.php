<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KompensasiEmisi;
use App\Models\EmisiCarbon;
use App\Notifications\NewKompensasiNotification;
use App\Models\Admin;

class KompensasiEmisiController extends Controller
{
    public function index()
    {
        // Ambil data emisi yang sudah diapprove
        $emisiApproved = DB::select("
            SELECT 
                e.kode_emisi_karbon,
                e.kategori_emisi_karbon,
                e.sub_kategori,
                e.nilai_aktivitas,
                e.faktor_emisi,
                e.kadar_emisi_karbon,
                e.deskripsi,
                e.status,
                e.tanggal_emisi,
                (e.kadar_emisi_karbon / 1000) as emisi_ton,
                COALESCE(SUM(k.jumlah_kompensasi), 0) / 1000 as kompensasi_ton,
                ((e.kadar_emisi_karbon - COALESCE(SUM(k.jumlah_kompensasi), 0)) / 1000) as sisa_emisi_ton
            FROM emisi_carbons e
            LEFT JOIN kompensasi_emisi k ON e.kode_emisi_karbon = k.kode_emisi_karbon
            WHERE e.status = 'approved'
            GROUP BY 
                e.kode_emisi_karbon,
                e.kategori_emisi_karbon,
                e.sub_kategori,
                e.nilai_aktivitas,
                e.faktor_emisi,
                e.kadar_emisi_karbon,
                e.deskripsi,
                e.status,
                e.tanggal_emisi
        ");

        // Ambil riwayat kompensasi
        $riwayatKompensasi = KompensasiEmisi::with('emisiCarbon')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($kompensasi) {
                return [
                    'kode_kompensasi' => $kompensasi->kode_kompensasi,
                    'kode_emisi_karbon' => $kompensasi->kode_emisi_karbon,
                    'jumlah_ton' => $kompensasi->jumlah_kompensasi / 1000,
                    'tanggal_kompensasi' => $kompensasi->tanggal_kompensasi,
                    'status' => $kompensasi->status,
                    'kategori_emisi' => $kompensasi->emisiCarbon->kategori_emisi_karbon ?? '-',
                    'sub_kategori' => $kompensasi->emisiCarbon->sub_kategori ?? '-'
                ];
            });

        // Ambil data kategori emisi untuk tabel
        $kategoriEmisi = collect($emisiApproved)->groupBy('kategori_emisi_karbon')
            ->map(function($items) {
                return [
                    'kategori' => $items->first()->kategori_emisi_karbon,
                    'total' => $items->sum('emisi_ton'),
                    'terkompensasi' => $items->sum('kompensasi_ton'),
                    'sisa' => $items->sum('sisa_emisi_ton')
                ];
            });

        return view('kompensasi.index', compact(
            'emisiApproved',
            'riwayatKompensasi',
            'kategoriEmisi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_emisi_karbon' => 'required|exists:emisi_carbons,kode_emisi_karbon',
            'jumlah_kompensasi' => 'required|numeric|min:0.001'
        ]);

        try {
            DB::beginTransaction();

            // Konversi ton ke kg untuk penyimpanan
            $jumlahKompensasiKg = $request->jumlah_kompensasi * 1000;

            // Cek sisa emisi yang belum terkompensasi untuk emisi karbon tertentu
            $emisiData = EmisiCarbon::where('kode_emisi_karbon', $request->kode_emisi_karbon)
                ->where('status', 'approved')
                ->first();

            if (!$emisiData) {
                DB::rollBack();
                return back()->with('error', 'Data emisi tidak ditemukan atau belum disetujui');
            }

            $totalKompensasi = KompensasiEmisi::where('kode_emisi_karbon', $request->kode_emisi_karbon)
                ->sum('jumlah_kompensasi');

            $sisaEmisiKg = $emisiData->kadar_emisi_karbon - $totalKompensasi;

            // Generate kode kompensasi
            $lastKode = KompensasiEmisi::orderBy('id', 'desc')->first();
            $kodeNumber = 1;
            if ($lastKode) {
                $kodeNumber = (int)substr($lastKode->kode_kompensasi, 4) + 1;
            }
            $kodeKompensasi = 'KMP-' . str_pad($kodeNumber, 6, '0', STR_PAD_LEFT);

            // Insert kompensasi menggunakan DB::insert
            $inserted = DB::insert("
                INSERT INTO kompensasi_emisi (
                    kode_kompensasi, kode_emisi_karbon, jumlah_kompensasi,
                    tanggal_kompensasi, status, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeKompensasi,
                    $request->kode_emisi_karbon,
                    $jumlahKompensasiKg,
                    now(),
                    'pending'
                ]
            );

            if (!$inserted) {
                DB::rollBack();
                return back()->with('error', 'Gagal menyimpan data kompensasi');
            }
            
            // // Send notification to all admins
            // $admins = Admin::all();
            // foreach ($admins as $admin) {
            //     $admin->notify(new NewKompensasiNotification($kompensasi));
            // }

            DB::commit();
            return redirect()->route('manager.kompensasi.index')
                           ->with('success', 'Kompensasi emisi berhasil dicatat dan menunggu persetujuan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($kodeKompensasi)
    {
        $kompensasi = KompensasiEmisi::with('emisiCarbon')
            ->where('kode_kompensasi', $kodeKompensasi)
            ->firstOrFail();

        return view('kompensasi.show', compact('kompensasi'));
    }

    public function update(Request $request, $kodeKompensasi)
    {
        $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $kompensasi = KompensasiEmisi::where('kode_kompensasi', $kodeKompensasi)->firstOrFail();
        $kompensasi->status = $request->status;
        $kompensasi->save();

        return back()->with('success', 'Status kompensasi berhasil diperbarui');
    }
}