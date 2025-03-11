<?php

namespace App\Http\Controllers;

use App\Models\PembelianCarbonCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;  
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\KompensasiEmisi;
use Illuminate\Support\Facades\Log;
use App\Models\PenyediaCarbonCredit;

class PembelianCarbonCreditController extends Controller
{
    public function index()
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;
        
        $carbon_credit = DB::select("
            SELECT pc.*, 
                   pcc.nama_penyedia,
                   pcc.mata_uang,
                   pcc.harga_per_ton as penyedia_harga_per_ton
            FROM pembelian_carbon_credits pc
            JOIN penyedia_carbon_credits pcc ON pc.kode_penyedia = pcc.kode_penyedia
            WHERE pc.kode_admin = ?
            ORDER BY pc.tanggal_pembelian_carbon_credit DESC",
            [$kodeAdmin]
        );

        // Tambahkan properti penyediaCarbonCredit untuk setiap record
        foreach($carbon_credit as $credit) {
            $credit->penyediaCarbonCredit = (object)[
                'nama_penyedia' => $credit->nama_penyedia,
                'mata_uang' => $credit->mata_uang,
                'harga_per_ton' => $credit->penyedia_harga_per_ton
            ];
        }

        return view('carbon_credit.index', compact('carbon_credit'));
    }

    public function create()
    {
        $kompensasiPending = DB::select("
            SELECT * FROM kompensasi_emisi 
            WHERE status = 'pending' 
            ORDER BY created_at DESC"
        );

        $penyediaList = DB::select("
            SELECT * FROM penyedia_carbon_credits 
            WHERE is_active = true 
            ORDER BY nama_penyedia ASC"
        );

        return view('carbon_credit.create', compact('kompensasiPending', 'penyediaList'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kompensasi' => 'required|exists:kompensasi_emisi,kode_kompensasi',
            'kode_penyedia' => 'required|exists:penyedia_carbon_credits,kode_penyedia',
            'jumlah_kompensasi' => 'required|numeric|min:0',
            'harga_per_ton' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'tanggal_pembelian_carbon_credit' => 'required|date',
            'bukti_pembelian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'deskripsi' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $lastKode = DB::selectOne("
                SELECT kode_pembelian_carbon_credit 
                FROM pembelian_carbon_credits 
                ORDER BY id DESC 
                LIMIT 1"
            );

            $kodeNumber = 1;
            if ($lastKode) {
                $kodeNumber = (int)substr($lastKode->kode_pembelian_carbon_credit, 4) + 1;
            }
            $kodePembelian = 'PCC-' . str_pad($kodeNumber, 4, '0', STR_PAD_LEFT);

        
            if ($request->hasFile('bukti_pembelian')) {
                $fileName = $kodePembelian . '.' . $request->file('bukti_pembelian')->getClientOriginalExtension();
                $path = 'bukti_pembelian/' . $fileName; 
                $request->file('bukti_pembelian')->storeAs('public/bukti_pembelian', $fileName);
            }

            $inserted = DB::insert("
                INSERT INTO pembelian_carbon_credits (
                    kode_pembelian_carbon_credit,
                    kode_kompensasi,
                    kode_penyedia,
                    jumlah_kompensasi,
                    harga_per_ton,
                    total_harga,
                    tanggal_pembelian_carbon_credit,
                    bukti_pembelian,
                    deskripsi,
                    kode_admin,
                    created_at,
                    updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodePembelian,
                    $validated['kode_kompensasi'],
                    $validated['kode_penyedia'],
                    $validated['jumlah_kompensasi'],
                    $validated['harga_per_ton'],
                    $validated['total_harga'],
                    $validated['tanggal_pembelian_carbon_credit'],
                    $path,
                    $validated['deskripsi'],
                    auth()->guard('admin')->user()->kode_admin
                ]
            );

            if (!$inserted) {
                throw new \Exception('Gagal menyimpan data pembelian');
            }

           
            $updated = DB::update("
                UPDATE kompensasi_emisi 
                SET status = 'completed', 
                    updated_at = NOW() 
                WHERE kode_kompensasi = ?",
                [$validated['kode_kompensasi']]
            );

            if (!$updated) {
                throw new \Exception('Gagal mengupdate status kompensasi');
            }

            DB::commit();

            return redirect()->route('carbon_credit.index')
                            ->with('success', 'Data pembelian carbon credit berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan pembelian', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function show(PembelianCarbonCredit $pembelianCarbonCredit)
    {
        return view('carbon_credit.create', compact('carbon_credit'));
    }


    public function edit($kode_pembelian_carbon_credit)
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;    
        
     
        $carbon_credit = DB::selectOne("
            SELECT pc.*, 
                   pcc.nama_penyedia,
                   pcc.mata_uang,
                   pcc.harga_per_ton as penyedia_harga_per_ton
            FROM pembelian_carbon_credits pc
            JOIN penyedia_carbon_credits pcc ON pc.kode_penyedia = pcc.kode_penyedia
            WHERE pc.kode_pembelian_carbon_credit = ? 
            AND pc.kode_admin = ?", 
            [$kode_pembelian_carbon_credit, $kodeAdmin]
        );

        if (!$carbon_credit) {
            abort(404);
        }

        
        $carbon_credit->penyediaCarbonCredit = (object)[
            'nama_penyedia' => $carbon_credit->nama_penyedia,
            'mata_uang' => $carbon_credit->mata_uang,
            'harga_per_ton' => $carbon_credit->penyedia_harga_per_ton
        ];

       
        $penyediaList = DB::select("
            SELECT * FROM penyedia_carbon_credits 
            WHERE is_active = true 
            ORDER BY nama_penyedia ASC"
        );

        return view('carbon_credit.edit', compact('carbon_credit', 'penyediaList'));
    }

    public function update(Request $request, $kode_pembelian_carbon_credit)
    {
        $validated = $request->validate([
            'tanggal_pembelian_carbon_credit' => 'required|date',
            'kode_penyedia' => 'required|exists:penyedia_carbon_credits,kode_penyedia',
            'jumlah_kompensasi' => 'required|numeric|min:0',
            'harga_per_ton' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'bukti_pembelian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        try {
            DB::beginTransaction();

           
            $carbon_credit = DB::selectOne("
                SELECT * FROM pembelian_carbon_credits 
                WHERE kode_pembelian_carbon_credit = ? 
                AND kode_admin = ?", 
                [$kode_pembelian_carbon_credit, Auth::guard('admin')->user()->kode_admin]
            );

            if (!$carbon_credit) {
                abort(404);
            }

            $buktiPembelianPath = $carbon_credit->bukti_pembelian;
            if ($request->hasFile('bukti_pembelian')) {
                
                if ($carbon_credit->bukti_pembelian) {
                    Storage::disk('public')->delete($carbon_credit->bukti_pembelian);
                }

                
                $fileName = $kode_pembelian_carbon_credit . '.' . $request->file('bukti_pembelian')->getClientOriginalExtension();
                $buktiPembelianPath = 'bukti_pembelian/' . $fileName; // Simpan path relatif
                $request->file('bukti_pembelian')->storeAs('public/bukti_pembelian', $fileName);
            }

           
            DB::update("
                UPDATE pembelian_carbon_credits 
                SET tanggal_pembelian_carbon_credit = ?,
                    kode_penyedia = ?,
                    jumlah_kompensasi = ?,
                    harga_per_ton = ?,
                    total_harga = ?,
                    deskripsi = ?,
                    bukti_pembelian = ?,
                    updated_at = NOW()
                WHERE kode_pembelian_carbon_credit = ? 
                AND kode_admin = ?",
                [
                    $validated['tanggal_pembelian_carbon_credit'],
                    $validated['kode_penyedia'],
                    $validated['jumlah_kompensasi'],
                    $validated['harga_per_ton'],
                    $validated['total_harga'],
                    $validated['deskripsi'],
                    $buktiPembelianPath,
                    $kode_pembelian_carbon_credit,
                    Auth::guard('admin')->user()->kode_admin
                ]
            );

            DB::commit();
            return redirect()->route('carbon_credit.index')
                            ->with('success', 'Data pembelian carbon credit berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

 
    public function destroy($kode_pembelian_carbon_credit)
    {
        try {
            DB::beginTransaction();
            
            $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

          
            $carbon_credit = DB::selectOne("
                SELECT pc.*, ke.status as status_kompensasi 
                FROM pembelian_carbon_credits pc
                JOIN kompensasi_emisi ke ON pc.kode_kompensasi = ke.kode_kompensasi
                WHERE pc.kode_pembelian_carbon_credit = ? 
                AND pc.kode_admin = ?", 
                [$kode_pembelian_carbon_credit, $kodeAdmin]
            );

            if (!$carbon_credit) {
                return back()->with('error', 'Data tidak ditemukan');
            }

           
            $kode_kompensasi = $carbon_credit->kode_kompensasi;

            
            if ($carbon_credit->bukti_pembelian) {
                Storage::disk('public')->delete($carbon_credit->bukti_pembelian);
            }

            $deleted = DB::delete("
                DELETE FROM pembelian_carbon_credits 
                WHERE kode_pembelian_carbon_credit = ? 
                AND kode_admin = ?",
                [$kode_pembelian_carbon_credit, $kodeAdmin]
            );

            if (!$deleted) {
                throw new \Exception('Gagal menghapus data pembelian');
            }

            $updated = DB::update("
                UPDATE kompensasi_emisi 
                SET status = 'pending',
                    updated_at = NOW()
                WHERE kode_kompensasi = ? 
                AND status = 'completed'", 
                [$kode_kompensasi]
            );

            
            Log::info('Update Kompensasi Status', [
                'kode_kompensasi' => $kode_kompensasi,
                'rows_affected' => $updated,
                'current_status' => $carbon_credit->status_kompensasi
            ]);

            if (!$updated) {
                throw new \Exception('Gagal mengupdate status kompensasi');
            }

            DB::commit();

            return redirect()
                ->route('carbon_credit.index')
                ->with('success', 'Data Pembelian Carbon Credit berhasil dihapus dan status kompensasi diubah menjadi pending');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in destroy method', [
                'error' => $e->getMessage(),
                'kode_pembelian' => $kode_pembelian_carbon_credit
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editStatus($kode_pembelian_carbon_credit)
    {
        $carbon_credit = DB::selectOne(query: "
            SELECT * FROM pembelian_carbon_credits 
            WHERE kode_pembelian_carbon_credit = ?", bindings: [$kode_pembelian_carbon_credit]);

        if (!$carbon_credit) {
            abort(404);
        }

        return view(view: 'carbon_credit.edit_status', data: compact(var_name: 'carbon_credit'));
    }

    public function updateStatus(Request $request, $kode_pembelian_carbon_credit)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $kodeAdmin = Auth::guard(name: 'admin')->user()->kode_admin;
        
        DB::update(query: "
            UPDATE pembelian_carbon_credits 
            SET status = ?,
                kode_admin = ?,
                updated_at = NOW()
            WHERE kode_pembelian_carbon_credit = ?",
            bindings: [$request->status, $kodeAdmin, $kode_pembelian_carbon_credit]
        );

        return redirect()->route(route: 'carbon_credit.index')
                        ->with(key: 'success', value: 'Status pembelian carbon credit berhasil diperbarui.');
    }    

    public function downloadReport()
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;
        
        
        $carbon_credits = DB::select("
            SELECT pcc.*, a.nama_admin 
            FROM pembelian_carbon_credits pcc
            JOIN admins a ON pcc.kode_admin = a.kode_admin
            WHERE pcc.kode_admin = ?
            ORDER BY pcc.tanggal_pembelian_carbon_credit DESC",
            [$kodeAdmin]
        );

        $totalPembelian = DB::selectOne("
            SELECT COALESCE(SUM(jumlah_kompensasi), 0) as total
            FROM pembelian_carbon_credits
            WHERE kode_admin = ?",
            [$kodeAdmin]
        )->total;

        $reportData = [
            'title' => 'Laporan Pembelian Carbon Credit',
            'date' => Carbon::now()->format('d/m/Y'),
            'admin' => Auth::guard('admin')->user()->nama_admin,
            'carbon_credits' => $carbon_credits,
            'total_pembelian' => $totalPembelian
        ];

     
        $pdf = PDF::loadView('carbon_credit.report', $reportData);
      
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-pembelian-carbon-credit-'.Carbon::now()->format('d-m-Y').'.pdf');
    }

    public function listReport()
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;
        
        $carbon_credits = DB::select("
            SELECT pc.*, 
                   pcc.nama_penyedia,
                   pcc.mata_uang,
                   pcc.harga_per_ton as penyedia_harga_per_ton
            FROM pembelian_carbon_credits pc
            JOIN penyedia_carbon_credits pcc ON pc.kode_penyedia = pcc.kode_penyedia
            WHERE pc.kode_admin = ?
            ORDER BY pc.tanggal_pembelian_carbon_credit DESC",
            [$kodeAdmin]
        );

        foreach($carbon_credits as $credit) {
            $credit->penyediaCarbonCredit = (object)[
                'nama_penyedia' => $credit->nama_penyedia,
                'mata_uang' => $credit->mata_uang,
                'harga_per_ton' => $credit->penyedia_harga_per_ton
            ];
        }

        return view('carbon_credit.list_report', compact('carbon_credits'));
    }

    public function downloadSelectedReport(Request $request)
    {
        $selectedCredit = $request->input('selected_credit', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if (empty($selectedCredit)) {
            return redirect()->back()->with('error', 'Pilih minimal satu pembelian untuk dicetak');
        }

        $placeholders = str_repeat('?,', count($selectedCredit) - 1) . '?';
        $params = $selectedCredit;
        
        
        $query = "
            SELECT pcc.*, a.nama_admin 
            FROM pembelian_carbon_credits pcc
            JOIN admins a ON pcc.kode_admin = a.kode_admin
            WHERE pcc.kode_pembelian_carbon_credit IN ($placeholders)
        ";

        if ($startDate && $endDate) {
            $query .= " AND pcc.tanggal_pembelian_carbon_credit BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        $query .= " ORDER BY pcc.tanggal_pembelian_carbon_credit DESC";
        
     
        $carbon_credits = DB::select($query, $params);

      
        $totalPembelian = DB::selectOne("
            SELECT COALESCE(SUM(jumlah_kompensasi), 0) as total
            FROM pembelian_carbon_credits
            WHERE kode_pembelian_carbon_credit IN ($placeholders)",
            $selectedCredit
        )->total;

        $reportData = [
            'title' => 'Laporan Pembelian Carbon Credit',
            'date' => Carbon::now()->format('d/m/Y'),
            'admin' => Auth::guard('admin')->user()->nama_admin,
            'carbon_credits' => $carbon_credits,
            'total_pembelian' => $totalPembelian,
            'start_date' => $startDate ? Carbon::parse($startDate)->format('d/m/Y') : null,
            'end_date' => $endDate ? Carbon::parse($endDate)->format('d/m/Y') : null
        ];

        $pdf = PDF::loadView('carbon_credit.report', $reportData);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('laporan-pembelian-carbon-credit-'.Carbon::now()->format('d-m-Y').'.pdf');
    }

   
    public function managerIndex()
    {
  
        $carbonCredits = DB::select("
            SELECT 
                pcc.kode_pembelian_carbon_credit,
                pcc.jumlah_kompensasi,
                pcc.tanggal_pembelian_carbon_credit,
                pcc.deskripsi,
                pcc.bukti_pembelian,
                pcc.kode_kompensasi,
                ke.status as status_kompensasi,
                ec.kategori_emisi_karbon,
                ec.sub_kategori
            FROM pembelian_carbon_credits pcc
            LEFT JOIN kompensasi_emisi ke ON pcc.kode_kompensasi = ke.kode_kompensasi
            LEFT JOIN emisi_carbons ec ON ke.kode_emisi_karbon = ec.kode_emisi_karbon
            ORDER BY pcc.tanggal_pembelian_carbon_credit DESC
        ");

     
        $carbonCredits = collect($carbonCredits)->map(function ($credit) {
            return [
                'kode_pembelian' => $credit->kode_pembelian_carbon_credit,
                'kategori' => $credit->kategori_emisi_karbon ?? '-',
                'sub_kategori' => $credit->sub_kategori ?? '-',
                'jumlah_kompensasi' => $credit->jumlah_kompensasi,
                'tanggal_pembelian' => $credit->tanggal_pembelian_carbon_credit,
                'status_kompensasi' => $credit->status_kompensasi ?? 'pending',
                'kode_kompensasi' => $credit->kode_kompensasi,
                'deskripsi' => $credit->deskripsi,
                'bukti_pembelian' => $credit->bukti_pembelian ? asset('storage/' . $credit->bukti_pembelian) : null
            ];
        });

        
        $summary = [
            'total_pembelian' => $carbonCredits->count(),
            'total_kompensasi' => $carbonCredits->sum('jumlah_kompensasi'),
            'completed_kompensasi' => $carbonCredits->where('status_kompensasi', 'completed')->count(),
            'pending_kompensasi' => $carbonCredits->where('status_kompensasi', 'pending')->count()
        ];

        return view('carbon_credit.manager.index', compact('carbonCredits', 'summary'));
    }
}
