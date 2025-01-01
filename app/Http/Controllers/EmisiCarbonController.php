<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\FaktorEmisi;
use App\Models\EmisiCarbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewEmisiNotification;
use App\Models\Admin;


class EmisiCarbonController extends Controller
{
    // Tambahkan method untuk mendapatkan satuan berdasarkan kategori
    private function getSatuan($kategori)
    {
        $satuan = [
            'Transportasi' => 'liter',
            'Listrik' => 'kWh',
            'Sampah' => 'kg',
            'Air' => 'm³'
        ];
        
        return $satuan[$kategori] ?? '';
    }

    private function konversiEmisi($kategori, $subKategori, $nilaiAktivitas)
    {
        // Ambil faktor emisi dari database
        $faktorEmisi = FaktorEmisi::where('kategori_emisi_karbon', $kategori)
            ->where('sub_kategori', $subKategori)
            ->first();

        if ($faktorEmisi) {
            $hasil = $nilaiAktivitas * $faktorEmisi->nilai_faktor;
            
            return [
                'nilai_aktivitas' => $nilaiAktivitas,
                'faktor_emisi' => $faktorEmisi->nilai_faktor,
                'hasil_konversi' => $hasil,
                'satuan_aktivitas' => $faktorEmisi->satuan,
                'satuan_emisi' => 'kg CO₂e'
            ];
        }
        
        // Return default values jika tidak ada faktor emisi
        return [
            'nilai_aktivitas' => $nilaiAktivitas,
            'faktor_emisi' => 0,
            'hasil_konversi' => 0,
            'satuan_aktivitas' => '',
            'satuan_emisi' => 'kg CO₂e'
        ];
    }

    private function getSatuanAktivitas($kategori)
    {
        $satuan = [
            'transportasi' => 'liter',
            'listrik' => 'kWh',
            'sampah' => 'kg',
            'air' => 'm³',
            'gas' => 'kg'
        ];
        
        return $satuan[$kategori] ?? '';
    }

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

        // Debug untuk memeriksa data
        foreach ($emisiCarbons as $emisi) {
            $emisi->konversi = $this->konversiEmisi(
                $emisi->kategori_emisi_karbon,
                $emisi->sub_kategori,
                $emisi->nilai_aktivitas
            );
           
            $emisi->satuan = $this->getSatuan($emisi->kategori_emisi_karbon);
        }

        return view('emisicarbon.index', compact('emisiCarbons'));
    }

    public function create()
    {
        // Ambil data kategori dan sub kategori dari tabel faktor_emisis
        $faktorEmisis = FaktorEmisi::all();
        
        // Kelompokkan berdasarkan kategori
        $kategoriEmisi = $faktorEmisis->groupBy('kategori_emisi_karbon');
        
        return view('emisicarbon.create', compact('kategoriEmisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_emisi' => 'required|date',
            'kategori_emisi_karbon' => 'required|string',
            'sub_kategori' => 'required|string',
            'nilai_aktivitas' => 'required|numeric|min:0',
            'deskripsi' => 'required|string'
        ]);

            $kodeEmisi = 'EMC-' . Str::random(6);
            $kodeUser = Auth::guard('pengguna')->user()->kode_user;

        // Ambil faktor emisi dari database
        $faktorEmisi = FaktorEmisi::where('kategori_emisi_karbon', $request->kategori_emisi_karbon)
            ->where('sub_kategori', $request->sub_kategori)
            ->first();

        Log::info('Faktor Emisi:', [
            'kategori' => $request->kategori_emisi_karbon,
            'sub_kategori' => $request->sub_kategori,
            'nilai_faktor' => $faktorEmisi ? $faktorEmisi->nilai_faktor : 'tidak ditemukan'
        ]);

        if (!$faktorEmisi) {
            return redirect()->back()->with('error', 'Faktor emisi tidak ditemukan');
        }

        try {
            DB::beginTransaction();

            DB::insert("
                INSERT INTO emisi_carbons (
                    kode_emisi_karbon, kategori_emisi_karbon, sub_kategori,
                    nilai_aktivitas, faktor_emisi, deskripsi, 
                    status, kode_user, tanggal_emisi,
                    created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeEmisi,
                    $request->kategori_emisi_karbon,
                    $request->sub_kategori,
                    $request->nilai_aktivitas,
                    $faktorEmisi->nilai_faktor, // Menggunakan nilai faktor dari database
                    $request->deskripsi,
                    'pending',
                    $kodeUser,
                    $request->tanggal_emisi
                ]
            );

            // // Send notification to all admins
            // $admins = Admin::all();
            // foreach ($admins as $admin) {
            //     $admin->notify(new NewEmisiNotification([
            //         'type' => 'emisi',
            //         'kode_emisi' => $emisiData->kode_emisi_karbon,
            //         'kategori' => $emisiData->kategori_emisi_karbon,
            //         'jumlah_ton' => number_format($emisiData->kadar_emisi_karbon / 1000, 3),
            //         'message' => 'Emisi karbon baru memerlukan persetujuan',
            //         'url' => route('admin.emissions.edit_status', $emisiData->kode_emisi_karbon)
            //     ]));
            // }

            DB::commit();
            return redirect()->route('emisicarbon.index')
                           ->with('success', 'Data emisi karbon berhasil disimpan dan menunggu persetujuan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($kode_emisi_karbon)
    {
        $kodeUser = Auth::guard('pengguna')->user()->kode_user;
        
        $emisiCarbon = EmisiCarbon::where('kode_emisi_karbon', $kode_emisi_karbon)
            ->where('kode_user', $kodeUser)
            ->firstOrFail();
        
        $faktorEmisis = FaktorEmisi::all();
        $kategoriEmisi = $faktorEmisis->groupBy('kategori_emisi_karbon');
        
        return view('emisicarbon.edit', compact('emisiCarbon', 'kategoriEmisi'));
    }

    public function update(Request $request, $kode_emisi_karbon)
    {
        $request->validate([
            'tanggal_emisi' => 'required|date',
            'kategori_emisi_karbon' => 'required|string',
            'sub_kategori' => 'required|string',
            'nilai_aktivitas' => 'required|numeric|min:0',
            'deskripsi' => 'required|string'
        ]);

        // Ambil faktor emisi dari database
        $faktorEmisi = FaktorEmisi::where('kategori_emisi_karbon', $request->kategori_emisi_karbon)
            ->where('sub_kategori', $request->sub_kategori)
            ->first();

        if (!$faktorEmisi) {
            return redirect()->back()->with('error', 'Faktor emisi tidak ditemukan');
        }

        $kodeUser = Auth::guard('pengguna')->user()->kode_user;
        
        DB::update("
            UPDATE emisi_carbons 
            SET tanggal_emisi = ?,
                kategori_emisi_karbon = ?,
                sub_kategori = ?,
                nilai_aktivitas = ?,
                faktor_emisi = ?,
                deskripsi = ?,
                status = 'pending',
                updated_at = NOW()
            WHERE kode_emisi_karbon = ? 
            AND kode_user = ?",
            [
                $request->tanggal_emisi,
                $request->kategori_emisi_karbon,
                $request->sub_kategori,
                $request->nilai_aktivitas,
                $faktorEmisi->nilai_faktor, // Menggunakan nilai faktor dari database
                $request->deskripsi,
                $kode_emisi_karbon,
                $kodeUser
            ]
        );

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
            SELECT ec.*, p.nama_user 
            FROM emisi_carbons ec
            LEFT JOIN penggunas p ON ec.kode_user = p.kode_user
            ORDER BY ec.created_at DESC 
            LIMIT 10"
        );
        
        foreach ($emisiCarbons as $emisi) {
            $emisi->satuan = $this->getSatuan($emisi->kategori_emisi_karbon);
        }
        
        return view('emisicarbon.admin.index', compact('emisiCarbons'));
    }

    public function downloadReport()
{
    // Get approved emissions
    $emisi_carbons = DB::select("
        SELECT 
            ec.*,
            p.nama_user 
        FROM emisi_carbons ec
        LEFT JOIN penggunas p ON ec.kode_user = p.kode_user
        WHERE ec.status = 'approved'
        ORDER BY ec.tanggal_emisi DESC"
    );

    // Calculate totals
    $total_pengajuan = count($emisi_carbons);
    $total_emisi = 0;

    // Process categories
    $emisiPerKategori = [];
    foreach ($emisi_carbons as $emisi) {
        $total_emisi += $emisi->kadar_emisi_karbon;
        
        if (!isset($emisiPerKategori[$emisi->kategori_emisi_karbon])) {
            $emisiPerKategori[$emisi->kategori_emisi_karbon] = [
                'total_emisi' => 0,
                'jumlah_pengajuan' => 0
            ];
        }
        
        $emisiPerKategori[$emisi->kategori_emisi_karbon]['total_emisi'] += $emisi->kadar_emisi_karbon;
        $emisiPerKategori[$emisi->kategori_emisi_karbon]['jumlah_pengajuan']++;
    }

    // Prepare report data
    $reportData = [
        'title' => 'Laporan Emisi Karbon',
        'date' => Carbon::now()->format('d/m/Y'),
        'admin' => Auth::guard('admin')->user()->nama_admin,
        'emisi_carbons' => $emisi_carbons,
        'emisi_per_kategori' => $emisiPerKategori,
        'total_emisi' => $total_emisi,
        'total_pengajuan' => $total_pengajuan
    ];

    $pdf = PDF::loadView('emisicarbon.report', $reportData);
    $pdf->setPaper('A4', 'portrait');
    
    return $pdf->download('laporan-emisi-karbon-'.Carbon::now()->format('d-m-Y').'.pdf');
}

    public function listReport()
    {
        $emisiCarbons = DB::select("
            SELECT e.*, p.nama_user 
            FROM emisi_carbons e
            LEFT JOIN penggunas p ON e.kode_user = p.kode_user
            ORDER BY e.tanggal_emisi DESC"
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
        
        // Updated query to match database schema
        $emisi_carbons = DB::select("
            SELECT 
                ec.*,
                p.nama_user,
                m.nama_manager,
                a.nama_admin as approved_by
            FROM emisi_carbons ec
            LEFT JOIN penggunas p ON ec.kode_user = p.kode_user
            LEFT JOIN managers m ON ec.kode_manager = m.kode_manager
            LEFT JOIN admins a ON ec.kode_admin = a.kode_admin
            WHERE ec.kode_emisi_karbon IN ($placeholders)
            ORDER BY ec.tanggal_emisi DESC",
            $selectedEmisi
        );
    
        // Calculate totals
        $total_pengajuan = count($emisi_carbons);
        $total_emisi = 0;
        
        // Process categories
        $emisiPerKategori = [];
        foreach ($emisi_carbons as $emisi) {
            $total_emisi += $emisi->kadar_emisi_karbon;
            
            if (!isset($emisiPerKategori[$emisi->kategori_emisi_karbon])) {
                $emisiPerKategori[$emisi->kategori_emisi_karbon] = [
                    'total_emisi' => 0,
                    'jumlah_pengajuan' => 0
                ];
            }
            
            $emisiPerKategori[$emisi->kategori_emisi_karbon]['total_emisi'] += $emisi->kadar_emisi_karbon;
            $emisiPerKategori[$emisi->kategori_emisi_karbon]['jumlah_pengajuan']++;
        }
    
        $reportData = [
            'title' => 'Laporan Emisi Karbon',
            'date' => Carbon::now()->format('d/m/Y'),
            'admin' => Auth::guard('admin')->user()->nama_admin,
            'emisi_carbons' => $emisi_carbons,
            'emisi_per_kategori' => $emisiPerKategori,
            'total_emisi' => $total_emisi,
            'total_pengajuan' => $total_pengajuan
        ];
    
        $pdf = PDF::loadView('emisicarbon.report', $reportData);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('laporan-emisi-karbon-'.Carbon::now()->format('d-m-Y').'.pdf');
    }
}
