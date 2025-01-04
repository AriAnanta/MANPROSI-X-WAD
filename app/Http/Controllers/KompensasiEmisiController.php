<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\KompensasiEmisi;
use App\Models\EmisiCarbon;
use App\Notifications\NewKompensasiNotification;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;

class KompensasiEmisiController extends Controller
{
    public function index(Request $request)
    {
     
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

     
        $query = "
            SELECT 
                k.kode_kompensasi,
                k.kode_emisi_karbon,
                ROUND(k.jumlah_kompensasi / 1000, 2) as jumlah_ton,
                k.tanggal_kompensasi,
                k.status,
                COALESCE(e.kategori_emisi_karbon, '-') as kategori_emisi,
                COALESCE(e.sub_kategori, '-') as sub_kategori,
                ROUND(e.kadar_emisi_karbon / 1000, 2) as kadar_emisi_ton
            FROM kompensasi_emisi k
            LEFT JOIN emisi_carbons e ON k.kode_emisi_karbon = e.kode_emisi_karbon
            WHERE 1=1
        ";
        
        $params = [];

       
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query .= " AND (
                k.kode_kompensasi LIKE ? OR 
                k.kode_emisi_karbon LIKE ? OR
                e.kategori_emisi_karbon LIKE ? OR 
                e.sub_kategori LIKE ?
            )";
            $searchParam = "%{$search}%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
        }

       
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query .= " AND DATE(k.tanggal_kompensasi) >= ?";
            $params[] = $request->start_date;
        }
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query .= " AND DATE(k.tanggal_kompensasi) <= ?";
            $params[] = $request->end_date;
        }

        if ($request->has('status') && !empty($request->status)) {
            $query .= " AND k.status = ?";
            $params[] = $request->status;
        }

        if ($request->has('kategori') && !empty($request->kategori)) {
            $query .= " AND e.kategori_emisi_karbon = ?";
            $params[] = $request->kategori;
        }

        $query .= " ORDER BY k.created_at DESC";

        $riwayatKompensasi = DB::select($query, $params);

      
        $kategoriEmisi = collect($emisiApproved)->groupBy('kategori_emisi_karbon')
            ->map(function($items) {
                return [
                    'kategori' => $items->first()->kategori_emisi_karbon,
                    'total' => $items->sum('emisi_ton'),
                    'terkompensasi' => $items->sum('kompensasi_ton'),
                    'sisa' => $items->sum('sisa_emisi_ton')
                ];
            });

        
        $kategoris = DB::select("
            SELECT DISTINCT kategori_emisi_karbon 
            FROM emisi_carbons 
            WHERE kategori_emisi_karbon IS NOT NULL
            ORDER BY kategori_emisi_karbon
        ");

        return view('kompensasi.index', compact(
            'emisiApproved',
            'riwayatKompensasi',
            'kategoriEmisi',
            'kategoris'
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

            $jumlahKompensasiKg = $request->jumlah_kompensasi * 1000;

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

            
            $lastKode = KompensasiEmisi::orderBy('id', 'desc')->first();
            $kodeNumber = 1;
            if ($lastKode) {
                $kodeNumber = (int)substr($lastKode->kode_kompensasi, 4) + 1;
            }
            $kodeKompensasi = 'KMP-' . str_pad($kodeNumber, 6, '0', STR_PAD_LEFT);

            
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
        $kompensasi = DB::select("
            SELECT 
                k.*,
                ROUND(k.jumlah_kompensasi / 1000, 2) as jumlah_ton,
                e.*,
                ROUND(e.kadar_emisi_karbon / 1000, 2) as kadar_emisi_ton,
                COALESCE(e.kategori_emisi_karbon, '-') as kategori_emisi,
                COALESCE(e.sub_kategori, '-') as sub_kategori
            FROM kompensasi_emisi k
            LEFT JOIN emisi_carbons e ON k.kode_emisi_karbon = e.kode_emisi_karbon
            WHERE k.kode_kompensasi = ?
            LIMIT 1
        ", [$kodeKompensasi]);

        if (empty($kompensasi)) {
            abort(404);
        }

        return view('kompensasi.show', ['kompensasi' => $kompensasi[0]]);
    }

    public function update(Request $request, $kodeKompensasi)
    {
        $request->validate([
            'jumlah_kompensasi' => 'required|numeric|min:0.001'
        ]);

        try {
            DB::beginTransaction();

            
            $jumlahKompensasiKg = $request->jumlah_kompensasi * 1000;

            $updated = DB::update("
                UPDATE kompensasi_emisi 
                SET jumlah_kompensasi = ?,
                    updated_at = NOW()
                WHERE kode_kompensasi = ? 
                AND status = 'pending'
            ", [$jumlahKompensasiKg, $kodeKompensasi]);

            if (!$updated) {
                DB::rollBack();
                return back()->with('error', 'Gagal mengupdate data kompensasi');
            }

            DB::commit();
            return redirect()->route('manager.kompensasi.index')
                            ->with('success', 'Data kompensasi berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($kodeKompensasi)
    {
        $deleted = DB::delete("
            DELETE FROM kompensasi_emisi 
            WHERE kode_kompensasi = ? 
            AND status = 'pending'
        ", [$kodeKompensasi]);

        if (!$deleted) {
            return back()->with('error', 'Gagal menghapus data kompensasi');
        }

        return redirect()->route('manager.kompensasi.index')
                        ->with('success', 'Data kompensasi berhasil dihapus');
    }

    public function edit($kodeKompensasi)
    {
        $kompensasi = DB::select("
            SELECT 
                k.*,
                ROUND(k.jumlah_kompensasi / 1000, 2) as jumlah_ton,
                e.*,
                ROUND(e.kadar_emisi_karbon / 1000, 2) as kadar_emisi_ton,
                COALESCE(e.kategori_emisi_karbon, '-') as kategori_emisi,
                COALESCE(e.sub_kategori, '-') as sub_kategori
            FROM kompensasi_emisi k
            LEFT JOIN emisi_carbons e ON k.kode_emisi_karbon = e.kode_emisi_karbon
            WHERE k.kode_kompensasi = ? AND k.status = 'pending'
            LIMIT 1
        ", [$kodeKompensasi]);

        if (empty($kompensasi)) {
            abort(404);
        }

        return view('kompensasi.edit', ['kompensasi' => $kompensasi[0]]);
    }

    public function report()
    {
    
        $kompensasi = DB::select("
            SELECT 
                k.kode_kompensasi,
                k.kode_emisi_karbon,
                ROUND(k.jumlah_kompensasi / 1000, 2) as jumlah_ton,
                k.tanggal_kompensasi,
                k.status,
                COALESCE(e.kategori_emisi_karbon, '-') as kategori_emisi,
                COALESCE(e.sub_kategori, '-') as sub_kategori
            FROM kompensasi_emisi k
            LEFT JOIN emisi_carbons e ON k.kode_emisi_karbon = e.kode_emisi_karbon
            ORDER BY k.tanggal_kompensasi DESC
        ");

        $total_kompensasi = collect($kompensasi)->sum('jumlah_ton');

        $data = [
            'title' => 'Laporan Riwayat Kompensasi Emisi',
            'date' => now()->format('d/m/Y H:i:s'),
            'kompensasi' => $kompensasi,
            'total_kompensasi' => $total_kompensasi
        ];

        $pdf = PDF::loadView('kompensasi.report', $data);
        
        return $pdf->download('laporan-kompensasi-' . date('Y-m-d') . '.pdf');
    }
}