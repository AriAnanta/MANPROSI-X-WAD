<?php

namespace App\Http\Controllers;

use App\Models\EmisiCarbon;
use Illuminate\Http\Request;


class EmisiCarbonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emisis = EmisiCarbon::all();
        return view('emisicarbon.index', compact('emisis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('emisicarbon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode_emisi_karbon' => 'required|string|max:5|unique:emisi_carbons',
            'kategori_emisi_karbon' => 'required|string',
            'tanggal_emisi' => 'required|date',
            'kadar_emisi_karbon' => 'required|integer',
            'deskripsi' => 'required|string',
            'status' => 'required|string',
        ]);

        EmisiCarbon::create($validateData);
        return redirect()->route('emisicarbon.index')->with('success', 'Data Emisi Karbon Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmisiCarbon $emisiCarbon)
    {
        $emisi = EmisiCarbon::findOrFail($emisiCarbon);
        return view('emisicarbon.show', compact('emisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $emisiCarbon = EmisiCarbon::findOrFail($id);
    return view('emisicarbon.edit', compact('emisiCarbon'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Mendapatkan model berdasarkan ID
        $emisi = EmisiCarbon::findOrFail($id); // Ambil data berdasarkan ID

    // Validasi input
        $request->validate([
            'kode_emisi_karbon' => 'required|unique:emisi_carbons,kode_emisi_karbon,' . $emisi->id,
            'kadar_emisi_karbon' => 'required|integer',
            'kategori_emisi_karbon' => 'required'
    ]);

    // Update data model
        $emisi->update($request->all());

    // Redirect setelah berhasil
        return redirect()->route('emisicarbon.index')->with('success', 'Data Emisi Karbon Diperbaharui');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    // Menghapus data berdasarkan model tunggal yang dipassing
    $emisi = EmisiCarbon::findOrFail($id);
    $emisi->delete();

    return redirect()->route('emisicarbon.index')->with('success', 'Data Emisi Karbon Dihapus');
}
}
