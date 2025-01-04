<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenyediaCarbonCreditController extends Controller
{
    public function index()
    {
        $penyediaList = DB::select("
            SELECT * FROM penyedia_carbon_credits 
            ORDER BY created_at DESC"
        );

        return view('penyedia.index', compact('penyediaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penyedia' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_per_ton' => 'required|numeric|min:0',
            'mata_uang' => 'required|string|in:IDR,USD'
        ]);

        try {
           
            $lastKode = DB::selectOne("
                SELECT kode_penyedia 
                FROM penyedia_carbon_credits 
                ORDER BY id DESC 
                LIMIT 1"
            );

            $kodeNumber = 1;
            if ($lastKode) {
                $kodeNumber = (int)substr($lastKode->kode_penyedia, 4) + 1;
            }
            $kodePenyedia = 'PCC-' . str_pad($kodeNumber, 4, '0', STR_PAD_LEFT);

            DB::insert("
                INSERT INTO penyedia_carbon_credits (
                    kode_penyedia,
                    nama_penyedia,
                    deskripsi,
                    harga_per_ton,
                    mata_uang,
                    is_active,
                    created_at,
                    updated_at
                ) VALUES (?, ?, ?, ?, ?, true, NOW(), NOW())",
                [
                    $kodePenyedia,
                    $validated['nama_penyedia'],
                    $validated['deskripsi'],
                    $validated['harga_per_ton'],
                    $validated['mata_uang']
                ]
            );

            return redirect()->route('manager.penyedia.index')
                           ->with('success', 'Penyedia carbon credit berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $kode_penyedia)
    {
        $validated = $request->validate([
            'nama_penyedia' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga_per_ton' => 'required|numeric|min:0',
            'mata_uang' => 'required|string|in:IDR,USD',
            'is_active' => 'required|boolean'
        ]);

        try {
            DB::update("
                UPDATE penyedia_carbon_credits 
                SET nama_penyedia = ?,
                    deskripsi = ?,
                    harga_per_ton = ?,
                    mata_uang = ?,
                    is_active = ?,
                    updated_at = NOW()
                WHERE kode_penyedia = ?",
                [
                    $validated['nama_penyedia'],
                    $validated['deskripsi'],
                    $validated['harga_per_ton'],
                    $validated['mata_uang'],
                    $validated['is_active'],
                    $kode_penyedia
                ]
            );

            return redirect()->route('manager.penyedia.index')
                           ->with('success', 'Penyedia carbon credit berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($kode_penyedia)
    {
        try {
            DB::delete("
                DELETE FROM penyedia_carbon_credits 
                WHERE kode_penyedia = ?", 
                [$kode_penyedia]
            );

            return redirect()->route('manager.penyedia.index')
                           ->with('success', 'Penyedia carbon credit berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 