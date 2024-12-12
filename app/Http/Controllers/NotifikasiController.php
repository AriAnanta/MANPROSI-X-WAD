<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notif = Notifikasi::all();
        return view('notifikasi.index', compact('notif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notifikasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode_notifikasi' => 'required|string|max:5|unique:notifikasi',
            'kategori_notifikasi' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
        ]);

        Notifikasi::create($validateData);
        return redirect()->route('notifikasi.index')->with('success', 'Data Notifikasi Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notifikasi $notifikasi)
    {
        $notif = Notifikasi::findOrFail($notifikasi);
        return view('notifikasi.create', compact('notif'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notifikasi $notifikasi)
    {
        $notif = Notifikasi::findOrFail($notifikasi);
        return view('nitifikasi.edit', compact('notif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notifikasi $notifikasi)
    {
        $notif = Notifikasi::findOrFail($notifikasi);
        $request->validate([
            'kode_notifikasi'=>'required|unique:notifikasi,kode_notifikasi,'.$notif->id,
            'kategori_notifikasi'=>'required|string',
            'deskripsi'=>'required',
            
        ]);
        $notif->update($request->all());
        return redirect()->route('notifikasi.index')->with('success','Data Notifikasi Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notifikasi $notifikasi)
    {
        $notif = Notifikasi::findOrFail($notifikasi);
        $notif->delete();
        return redirect()->route('notifikasi.index')->with('success','Data Notifikasi Dihapus');
    }
}
