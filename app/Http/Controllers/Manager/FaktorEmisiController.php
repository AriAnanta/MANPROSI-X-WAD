<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FaktorEmisi;

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

        FaktorEmisi::create($request->all());

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

        $faktorEmisi = FaktorEmisi::findOrFail($id);
        $faktorEmisi->update($request->all());

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