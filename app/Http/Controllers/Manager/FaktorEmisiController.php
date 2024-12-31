<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaktorEmisi;
use Illuminate\Support\Facades\DB;

class FaktorEmisiController extends Controller
{
    public function index()
    {
        $faktorEmisis = FaktorEmisi::all();
        return view('pages.manager.faktor-emisi.index', compact('faktorEmisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_emisi_karbon' => 'required',
            'sub_kategori' => 'required',
            'nilai_faktor' => 'required|numeric',
            'satuan' => 'required'
        ]);

        DB::insert("
            INSERT INTO faktor_emisis (
                kategori_emisi_karbon,
                sub_kategori,
                nilai_faktor,
                satuan,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, NOW(), NOW())",
            [
                $request->kategori_emisi_karbon,
                $request->sub_kategori,
                $request->nilai_faktor,
                $request->satuan
            ]
        );

        return redirect()->route('manager.faktor-emisi.index')
            ->with('success', 'Faktor emisi berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_emisi_karbon' => 'required',
            'sub_kategori' => 'required',
            'nilai_faktor' => 'required|numeric',
            'satuan' => 'required'
        ]);

        DB::update("
            UPDATE faktor_emisis 
            SET kategori_emisi_karbon = ?,
                sub_kategori = ?,
                nilai_faktor = ?,
                satuan = ?,
                updated_at = NOW()
            WHERE id = ?",
            [
                $request->kategori_emisi_karbon,
                $request->sub_kategori,
                $request->nilai_faktor,
                $request->satuan,
                $id
            ]
        );

        return redirect()->route('manager.faktor-emisi.index')
            ->with('success', 'Faktor emisi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $faktorEmisi = FaktorEmisi::findOrFail($id);
        $faktorEmisi->delete();

        return redirect()->route('manager.faktor-emisi.index')
            ->with('success', 'Faktor emisi berhasil dihapus');
    }
} 