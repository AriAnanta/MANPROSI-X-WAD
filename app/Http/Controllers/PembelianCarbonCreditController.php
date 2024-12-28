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
<<<<<<< HEAD
=======
use Illuminate\Support\Facades\Log;
>>>>>>> fa3fd670cc780c4d9894654f8e0b5205c88b78c3

class PembelianCarbonCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kodeAdmin = Auth::guard(name: 'admin')->user()->kode_admin;
        $carbon_credit = DB::select(query: "   
            SELECT * FROM pembelian_carbon_credits 
            WHERE kode_admin = ? 
            ORDER BY created_at DESC 
            LIMIT 10", bindings: [$kodeAdmin]);
        return view(view: 'carbon_credit.index', data: compact(var_name: 'carbon_credit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kompensasiPending = KompensasiEmisi::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('carbon_credit.create', compact('kompensasiPending'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'kode_kompensasi' => 'required|exists:kompensasi_emisi,kode_kompensasi',
            'tanggal_pembelian_carbon_credit' => 'required|date',
            'jumlah_kompensasi' => 'required|numeric|min:0.01',
            'bukti_pembelian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'deskripsi' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // Generate kode pembelian
            $lastKode = PembelianCarbonCredit::orderBy('id', 'desc')->first();
            $kodeNumber = 1;
            if ($lastKode) {
                $kodeNumber = (int)substr($lastKode->kode_pembelian_carbon_credit, 4) + 1;
            }
            $kodePembelian = 'PCB-' . str_pad($kodeNumber, 6, '0', STR_PAD_LEFT);

            // Upload bukti pembelian
            $buktiPath = $request->file('bukti_pembelian')->store('bukti_pembelian', 'public');

            // Simpan data pembelian
            $pembelian = PembelianCarbonCredit::create([
                'kode_pembelian_carbon_credit' => $kodePembelian,
                'kode_kompensasi' => $request->kode_kompensasi,
                'jumlah_kompensasi' => $request->jumlah_kompensasi,
                'tanggal_pembelian_carbon_credit' => $request->tanggal_pembelian_carbon_credit,
                'bukti_pembelian' => $buktiPath,
                'deskripsi' => $request->deskripsi,
                'kode_admin' => auth()->guard('admin')->user()->kode_admin
            ]);

            if (!$pembelian) {
                throw new \Exception('Gagal menyimpan data pembelian');
            }

            // Update status kompensasi
            $kompensasi = KompensasiEmisi::where('kode_kompensasi', $request->kode_kompensasi)->first();
            if ($kompensasi) {
                $kompensasi->status = 'completed';
                if (!$kompensasi->save()) {
                    throw new \Exception('Gagal mengupdate status kompensasi');
                }
            }

            DB::commit();

            // Tambahkan log untuk debugging
<<<<<<< HEAD
            \Log::info('Pembelian berhasil disimpan', [
=======
            Log::info('Pembelian berhasil disimpan', [
>>>>>>> fa3fd670cc780c4d9894654f8e0b5205c88b78c3
                'pembelian_id' => $pembelian->id,
                'kode_pembelian' => $kodePembelian,
                'data' => $request->all()
            ]);

            return redirect()->route('carbon_credit.index')
                            ->with('success', 'Data pembelian carbon credit berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Tambahkan log error
<<<<<<< HEAD
            \Log::error('Error saat menyimpan pembelian', [
=======
            Log::error('Error saat menyimpan pembelian', [
>>>>>>> fa3fd670cc780c4d9894654f8e0b5205c88b78c3
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PembelianCarbonCredit $pembelianCarbonCredit)
    {
        return view('carbon_credit.create', compact('carbon_credit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_pembelian_carbon_credit)
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;    
        $carbon_credit = DB::selectOne("
            SELECT * FROM pembelian_carbon_credits 
            WHERE kode_pembelian_carbon_credit = ? 
            AND kode_admin = ?", 
            [$kode_pembelian_carbon_credit, $kodeAdmin]
        );

        if (!$carbon_credit) {
            abort(404);
        }

        return view('carbon_credit.edit', compact('carbon_credit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_pembelian_carbon_credit)
    {
        $request->validate([
            'tanggal_pembelian_carbon_credit' => 'required|date',
            'jumlah_kompensasi' => 'required|numeric',
            'deskripsi' => 'required',
            'bukti_pembelian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

        // Ambil data existing
        $carbon_credit = DB::selectOne("
            SELECT * FROM pembelian_carbon_credits 
            WHERE kode_pembelian_carbon_credit = ? 
            AND kode_admin = ?", 
            [$kode_pembelian_carbon_credit, $kodeAdmin]
        );

        if (!$carbon_credit) {
            abort(404);
        }

        // Handle file upload jika ada file baru
        $buktiPembelianPath = $carbon_credit->bukti_pembelian;
        if ($request->hasFile('bukti_pembelian')) {
            // Hapus file lama jika ada
            if ($carbon_credit->bukti_pembelian) {
                Storage::disk('public')->delete($carbon_credit->bukti_pembelian);
            }

            // Upload file baru
            $file = $request->file('bukti_pembelian');
            $fileName = $kode_pembelian_carbon_credit . '-' . time() . '.' . $file->getClientOriginalExtension();
            $buktiPembelianPath = $file->storeAs('bukti-pembelian', $fileName, 'public');
        }

        $updated = DB::update("
            UPDATE pembelian_carbon_credits 
            SET tanggal_pembelian_carbon_credit = ?,
                jumlah_kompensasi = ?,
                deskripsi = ?,
                bukti_pembelian = ?,
                updated_at = NOW()
            WHERE kode_pembelian_carbon_credit = ? 
            AND kode_admin = ?",
            [
                $request->tanggal_pembelian_carbon_credit,
                $request->jumlah_kompensasi,
                $request->deskripsi,
                $buktiPembelianPath,
                $kode_pembelian_carbon_credit,
                $kodeAdmin
            ]
        );

        if (!$updated) {    
            abort(404);
        }

        return redirect()
            ->route('carbon_credit.index')
            ->with('success', 'Data Pembelian Carbon Credit Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_pembelian_carbon_credit)
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

        // Ambil data existing
        $carbon_credit = DB::selectOne("
            SELECT * FROM pembelian_carbon_credits 
            WHERE kode_pembelian_carbon_credit = ? 
            AND kode_admin = ?", 
            [$kode_pembelian_carbon_credit, $kodeAdmin]
        );

        if (!$carbon_credit) {
            abort(404);
        }

        // Hapus file bukti pembelian jika ada
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
            abort(404);
        }

        return redirect()
            ->route('carbon_credit.index')
            ->with('success', 'Data Pembelian Carbon Credit Dihapus');
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
        
        // Ambil data pembelian carbon credit
        $carbon_credits = DB::select("
            SELECT pcc.*, a.nama_admin 
            FROM pembelian_carbon_credits pcc
            JOIN admins a ON pcc.kode_admin = a.kode_admin
            WHERE pcc.kode_admin = ?
            ORDER BY pcc.tanggal_pembelian_carbon_credit DESC",
            [$kodeAdmin]
        );

        // Hitung total pembelian
        $totalPembelian = DB::selectOne("
            SELECT COALESCE(SUM(jumlah_kompensasi), 0) as total
            FROM pembelian_carbon_credits
            WHERE kode_admin = ?",
            [$kodeAdmin]
        )->total;

        // Data untuk header laporan
        $reportData = [
            'title' => 'Laporan Pembelian Carbon Credit',
            'date' => Carbon::now()->format('d/m/Y'),
            'admin' => Auth::guard('admin')->user()->nama_admin,
            'carbon_credits' => $carbon_credits,
            'total_pembelian' => $totalPembelian
        ];

        // Generate PDF
        $pdf = PDF::loadView('carbon_credit.report', $reportData);
        
        // Set paper size ke A4
        $pdf->setPaper('A4', 'portrait');

        // Download PDF dengan nama yang dinamis
        return $pdf->download('laporan-pembelian-carbon-credit-'.Carbon::now()->format('d-m-Y').'.pdf');
    }

    public function listReport()
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;
        
        // Ambil semua data pembelian carbon credit
        $carbon_credits = DB::select("
            SELECT * FROM pembelian_carbon_credits
            WHERE kode_admin = ?
            ORDER BY tanggal_pembelian_carbon_credit DESC",
            [$kodeAdmin]
        );
        
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
        
        // Base query
        $query = "
            SELECT pcc.*, a.nama_admin 
            FROM pembelian_carbon_credits pcc
            JOIN admins a ON pcc.kode_admin = a.kode_admin
            WHERE pcc.kode_pembelian_carbon_credit IN ($placeholders)
        ";

        // Add date range if provided
        if ($startDate && $endDate) {
            $query .= " AND pcc.tanggal_pembelian_carbon_credit BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        $query .= " ORDER BY pcc.tanggal_pembelian_carbon_credit DESC";
        
        // Get selected data
        $carbon_credits = DB::select($query, $params);

        // Calculate total
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

    // Method untuk manager
    public function managerIndex()
    {
        // Get all carbon credit purchases with their related kompensasi
        $carbonCredits = DB::select("
            SELECT 
                pcc.*,
                ke.kode_kompensasi,
                ke.jumlah_kompensasi,
                ke.status as kompensasi_status,
                ec.kategori_emisi_karbon,
                ec.sub_kategori
            FROM pembelian_carbon_credits pcc
            JOIN kompensasi_emisi ke ON pcc.kode_kompensasi = ke.kode_kompensasi
            LEFT JOIN emisi_carbons ec ON ke.kode_emisi_karbon = ec.kode_emisi_karbon
            ORDER BY pcc.tanggal_pembelian_carbon_credit DESC
        ");

        // Transform data for view
        $carbonCredits = collect($carbonCredits)->map(function($credit) {
            return [
                'kode_pembelian' => $credit->kode_pembelian_carbon_credit,
                'kode_kompensasi' => $credit->kode_kompensasi,
                'kategori' => $credit->kategori_emisi_karbon,
                'sub_kategori' => $credit->sub_kategori,
                'jumlah_kompensasi' => number_format($credit->jumlah_kompensasi / 1000, 3), // Convert to tons
                'tanggal_pembelian' => Carbon::parse($credit->tanggal_pembelian_carbon_credit)->format('d/m/Y'),
                'status_kompensasi' => $credit->kompensasi_status,
                'bukti_pembelian' => $credit->bukti_pembelian,
                'deskripsi' => $credit->deskripsi
            ];
        });

        // Get summary data
        $summary = [
            'total_pembelian' => collect($carbonCredits)->count(),
            'total_kompensasi' => collect($carbonCredits)
                ->sum(function($credit) {
                    return floatval(str_replace(',', '', $credit['jumlah_kompensasi']));
                }),
            'completed_kompensasi' => collect($carbonCredits)
                ->where('status_kompensasi', 'completed')
                ->count(),
            'pending_kompensasi' => collect($carbonCredits)
                ->where('status_kompensasi', 'pending')
                ->count()
        ];

        return view('carbon_credit.manager.index', compact('carbonCredits', 'summary'));
    }
}
