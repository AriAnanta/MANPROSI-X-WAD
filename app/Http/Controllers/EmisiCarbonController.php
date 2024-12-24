<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EmisiCarbonController extends Controller
{
    public function index()
    {
        $kodeUser = Auth::guard('pengguna')->user()->kode_user;
        $emisiCarbons = DB::select("
            SELECT * FROM emisi_carbons 
            WHERE kode_user = ? 
            ORDER BY created_at DESC
            LIMIT 10", 
            [$kodeUser]
        );
        return view('emisicarbon.index', compact('emisiCarbons'));
    }

    public function create()
    {
        return view('emisicarbon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_emisi' => 'required|date',
            'kategori_emisi_karbon' => 'required|string',
            'kadar_emisi_karbon' => 'required|numeric|min:0',
            'deskripsi' => 'required|string'
        ]);

        $kodeEmisi = 'EMC-' . Str::random(6);
        $kodeUser = Auth::guard('pengguna')->user()->kode_user;

        DB::insert("
            INSERT INTO emisi_carbons (
                kode_emisi_karbon, kategori_emisi_karbon, tanggal_emisi,
                kadar_emisi_karbon, deskripsi, status, kode_user,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $kodeEmisi,
                $request->kategori_emisi_karbon,
                $request->tanggal_emisi,
                $request->kadar_emisi_karbon,
                $request->deskripsi,
                'pending',
                $kodeUser
            ]
        );

        return redirect()->route('emisicarbon.index')
                        ->with('success', 'Data emisi karbon berhasil ditambahkan.');
    }

    public function edit($kode_emisi_karbon)
    {
        $kodeUser = Auth::guard('pengguna')->user()->kode_user;
        $emisiCarbon = DB::selectOne("
            SELECT * FROM emisi_carbons 
            WHERE kode_emisi_karbon = ? 
            AND kode_user = ?", 
            [$kode_emisi_karbon, $kodeUser]
        );

        if (!$emisiCarbon) {
            abort(404);
        }

        return view('emisicarbon.edit', compact('emisiCarbon'));
    }

    public function update(Request $request, $kode_emisi_karbon)
    {
        $request->validate([
            'tanggal_emisi' => 'required|date',
            'kategori_emisi_karbon' => 'required|string',
            'kadar_emisi_karbon' => 'required|numeric|min:0',
            'deskripsi' => 'required|string'
        ]);

        $kodeUser = Auth::guard('pengguna')->user()->kode_user;
        
        $updated = DB::update("
            UPDATE emisi_carbons 
            SET tanggal_emisi = ?,
                kategori_emisi_karbon = ?,
                kadar_emisi_karbon = ?,
                deskripsi = ?,
                updated_at = NOW()
            WHERE kode_emisi_karbon = ? 
            AND kode_user = ?",
            [
                $request->tanggal_emisi,
                $request->kategori_emisi_karbon,
                $request->kadar_emisi_karbon,
                $request->deskripsi,
                $kode_emisi_karbon,
                $kodeUser
            ]
        );

        if (!$updated) {
            abort(404);
        }

        return redirect()->route('emisicarbon.index')
                        ->with('success', 'Data emisi karbon berhasil diperbarui.');
    }

    public function destroy($kode_emisi_karbon)
    {
        $kodeUser = Auth::guard('pengguna')->user()->kode_user;
        
        $deleted = DB::delete("
            DELETE FROM emisi_carbons 
            WHERE kode_emisi_karbon = ? 
            AND kode_user = ?",
            [$kode_emisi_karbon, $kodeUser]
        );

        if (!$deleted) {
            abort(404);
        }

        return redirect()->route('emisicarbon.index')
                        ->with('success', 'Data emisi karbon berhasil dihapus.');
    }

    public function editStatus($kode_emisi_karbon)
    {
        $emisiCarbon = DB::selectOne("
            SELECT * FROM emisi_carbons 
            WHERE kode_emisi_karbon = ?", 
            [$kode_emisi_karbon]
        );

        if (!$emisiCarbon) {
            abort(404);
        }

        return view('emisicarbon.edit_status', compact('emisiCarbon'));
    }

    public function updateStatus(Request $request, $kode_emisi_karbon)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;
        
        DB::update("
            UPDATE emisi_carbons 
            SET status = ?,
                kode_admin = ?,
                updated_at = NOW()
            WHERE kode_emisi_karbon = ?",
            [$request->status, $kodeAdmin, $kode_emisi_karbon]
        );

        return redirect()->route('admin.emissions.index')
                        ->with('success', 'Status emisi karbon berhasil diperbarui.');
    }

    public function adminIndex()
    {
        $emisiCarbons = DB::select("
            SELECT * FROM emisi_carbons 
            ORDER BY created_at DESC 
            LIMIT 10"
        );
        
        return view('emisicarbon.admin.index', compact('emisiCarbons'));
    }

    public function downloadReport()
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;
        
        // Ambil data emisi carbon dengan join ke tabel pengguna
        $emisi_carbons = DB::select("
            SELECT ec.*, p.nama_user 
            FROM emisi_carbons ec
            LEFT JOIN penggunas p ON ec.kode_user = p.kode_user
            WHERE ec.status = 'approved'
            ORDER BY ec.tanggal_emisi DESC",
            []
        );

        // Hitung total emisi
        $totalEmisi = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon), 0) as total
            FROM emisi_carbons
            WHERE status = 'approved'"
        )->total;

        // Hitung total per kategori
        $emisiPerKategori = DB::select("
            SELECT kategori_emisi_karbon,
                   COUNT(*) as jumlah_pengajuan,
                   COALESCE(SUM(kadar_emisi_karbon), 0) as total_emisi
            FROM emisi_carbons
            WHERE status = 'approved'
            GROUP BY kategori_emisi_karbon
            ORDER BY total_emisi DESC"
        );

        // Data untuk header laporan
        $reportData = [
            'title' => 'Laporan Emisi Karbon',
            'date' => Carbon::now()->format('d/m/Y'),
            'admin' => Auth::guard('admin')->user()->nama_admin,
            'emisi_carbons' => $emisi_carbons,
            'total_emisi' => $totalEmisi,
            'emisi_per_kategori' => $emisiPerKategori
        ];

        // Generate PDF
        $pdf = PDF::loadView('emisicarbon.report', $reportData);
        
        // Set paper size ke A4
        $pdf->setPaper('A4', 'portrait');

        // Download PDF dengan nama yang dinamis
        return $pdf->download('laporan-emisi-karbon-'.Carbon::now()->format('d-m-Y').'.pdf');
    }

    public function listReport()
    {
        // Ambil semua data emisi dengan informasi pengguna
        $emisiCarbons = DB::select("
            SELECT ec.*, p.nama_user 
            FROM emisi_carbons ec
            LEFT JOIN penggunas p ON ec.kode_user = p.kode_user
            ORDER BY ec.tanggal_emisi DESC"
        );
        
        return view('emisicarbon.list_report', compact('emisiCarbons'));
    }

    public function downloadSelectedReport(Request $request)
    {
        $selectedEmisi = $request->input('selected_emisi', []);
        
        if (empty($selectedEmisi)) {
            return redirect()->back()->with('error', 'Pilih minimal satu emisi untuk dicetak');
        }

        $placeholders = str_repeat('?,', count($selectedEmisi) - 1) . '?';
        
        // Ambil data emisi yang dipilih
        $emisi_carbons = DB::select("
            SELECT ec.*, p.nama_user 
            FROM emisi_carbons ec
            LEFT JOIN penggunas p ON ec.kode_user = p.kode_user
            WHERE ec.kode_emisi_karbon IN ($placeholders)
            ORDER BY ec.tanggal_emisi DESC",
            $selectedEmisi
        );

        // Hitung total emisi
        $totalEmisi = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon), 0) as total
            FROM emisi_carbons
            WHERE kode_emisi_karbon IN ($placeholders)",
            $selectedEmisi
        )->total;

        // Hitung total per kategori
        $emisiPerKategori = DB::select("
            SELECT kategori_emisi_karbon,
                   COUNT(*) as jumlah_pengajuan,
                   COALESCE(SUM(kadar_emisi_karbon), 0) as total_emisi
            FROM emisi_carbons
            WHERE kode_emisi_karbon IN ($placeholders)
            GROUP BY kategori_emisi_karbon
            ORDER BY total_emisi DESC",
            $selectedEmisi
        );

        $reportData = [
            'title' => 'Laporan Emisi Karbon',
            'date' => Carbon::now()->format('d/m/Y'),
            'admin' => Auth::guard('admin')->user()->nama_admin,
            'emisi_carbons' => $emisi_carbons,
            'total_emisi' => $totalEmisi,
            'emisi_per_kategori' => $emisiPerKategori
        ];

        $pdf = PDF::loadView('emisicarbon.report', $reportData);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('laporan-emisi-karbon-'.Carbon::now()->format('d-m-Y').'.pdf');
    }
}
