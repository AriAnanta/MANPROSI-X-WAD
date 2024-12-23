<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
}
