<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notifikasi;
use App\Models\Pengguna;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\RequestCarbonCredit;
use App\Models\Notification;

class NotifikasiController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Notifikasi::with('pengguna');

        if ($request->filled('tujuan')) {
            $query->where('kode_user', $request->tujuan);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_notifikasi', $request->kategori);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $notifikasi = $query->get();
        $penggunas = Pengguna::all();

        return view('notifikasi.index', compact('notifikasi', 'penggunas'));
    }

   
    public function create()
    {
        $users = DB::table('penggunas')->get(); 
        return view('notifikasi.create', compact('users'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kategori_notifikasi' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'kode_user' => 'required|string', 
        ]);

        $lastNotif = DB::table('notifikasis')
            ->where('kode_notifikasi', 'like', 'NTF-%')
            ->orderBy('kode_notifikasi', 'desc')
            ->first();

        if ($lastNotif) {
            $lastNumber = intval(substr($lastNotif->kode_notifikasi, 4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $kode_notifikasi = 'NTF-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        if ($validatedData['kode_user'] === 'all') {
            $validatedData['kode_user'] = NULL;
        }

        $kode_admin = Auth::guard('admin')->user()->kode_admin;

        DB::table('notifikasis')->insert([
            'kode_notifikasi' => $kode_notifikasi,
            'kategori_notifikasi' => $validatedData['kategori_notifikasi'],
            'deskripsi' => $validatedData['deskripsi'],
            'tanggal' => $validatedData['tanggal'],
            'kode_user' => $validatedData['kode_user'],
            'kode_admin' => $kode_admin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dibuat');
    }

    
    public function show($id)
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

        $notifikasi = DB::selectOne("
            SELECT * FROM notifikasis 
            WHERE id = ? AND kode_admin = ?
            ORDER BY tanggal DESC"
            ,
            
            [$id, $kodeAdmin]
        );

        if (!$notifikasi) {
            abort(404);
        }

        return view('notifikasi.show', compact('notifikasi'));
    }

    
    public function edit($id)
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

        $notifikasi = DB::selectOne("
            SELECT * FROM notifikasis
            WHERE id = ? AND kode_admin = ?",
            [$id, $kodeAdmin]
        );

        if (!$notifikasi) {
            abort(404);
        }

        return view('notifikasi.edit', compact('notifikasi'));
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_notifikasi' => "required|string|max:7|unique:notifikasis,kode_notifikasi,{$id}",
            'kategori_notifikasi' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

        $updated = DB::update("
            UPDATE notifikasis 
            SET kode_notifikasi = ?, kategori_notifikasi = ?, tanggal = ?, deskripsi = ?, updated_at = NOW()
            WHERE id = ? AND kode_admin = ?",
            [
                $request->kode_notifikasi,
                $request->kategori_notifikasi,
                $request->tanggal,
                $request->deskripsi,
                $id,
                $kodeAdmin
            ]
        );

        if (!$updated) {
            abort(404);
        }

        return redirect()->route('notifikasi.index')->with('success', 'Data Notifikasi Diperbarui');
    }

   
    public function destroy($id)
    {
        $kodeAdmin = Auth::guard('admin')->user()->kode_admin;

        $notifikasi = DB::selectOne("
            SELECT * FROM notifikasis 
            WHERE id = ? AND kode_admin = ?",
            [$id, $kodeAdmin]
        );

        if (!$notifikasi) {
            abort(404);
        }

        $deleted = DB::delete("
            DELETE FROM notifikasis 
            WHERE id = ? AND kode_admin = ?",
            [$id, $kodeAdmin]
        );

        if (!$deleted) {
            abort(404);
        }

        return redirect()->route('notifikasi.index')->with('success', 'Data Notifikasi Dihapus');
    }

    public function report()
    {
        $notifikasi = Notifikasi::with('pengguna')->get();
        $pdf = PDF::loadView('notifikasi.report', compact('notifikasi'));
        return $pdf->stream('laporan-notifikasi.pdf');
    }

    public function requestCredit(Request $request)
    {
        $validated = $request->validate([
            'kategori_emisi' => 'required|string',
            'sub_kategori' => 'required|string',
            'jumlah_kredit' => 'required|numeric|min:0.001',
            'prioritas' => 'required|in:tinggi,sedang,rendah',
            'catatan' => 'nullable|string'
        ]);

       
        $requestId = DB::insert("
            INSERT INTO request_carbon_credits (
                kategori_emisi, 
                sub_kategori,
                jumlah_kredit,
                prioritas,
                catatan,
                status,
                user_id,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, ?, 'pending', ?, NOW(), NOW())",
            [
                $validated['kategori_emisi'],
                $validated['sub_kategori'], 
                $validated['jumlah_kredit'],
                $validated['prioritas'],
                $validated['catatan'],
                Auth::guard('manager')->id()
            ]
        );

        DB::insert("
            INSERT INTO notifications (
                title,
                message,
                type,
                priority,
                status,
                data,
                for_role,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, 'unread', ?, 'admin', NOW(), NOW())",
            [
                'Request Pembelian Carbon Credit Baru',
                "Request pembelian carbon credit untuk kategori {$validated['kategori_emisi']} - {$validated['sub_kategori']} sebesar {$validated['jumlah_kredit']} ton CO₂e",
                'request_credit',
                $validated['prioritas'],
                json_encode([
                    'request_id' => $requestId,
                    'kategori' => $validated['kategori_emisi'],
                    'sub_kategori' => $validated['sub_kategori'],
                    'jumlah' => $validated['jumlah_kredit'],
                    'catatan' => $validated['catatan']
                ])
            ]
        );

        return redirect()->back()->with('success', 'Request pembelian carbon credit berhasil dikirim ke admin');
    }
}