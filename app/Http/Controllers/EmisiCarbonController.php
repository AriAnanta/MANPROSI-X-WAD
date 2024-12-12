<?php

namespace App\Http\Controllers;

use App\Models\EmisiCarbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmisiCarbonController extends Controller
{
    public function index()
    {
        $emisiCarbons = EmisiCarbon::where('kode_user', Auth::guard('pengguna')->user()->kode_user)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);
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

        // Generate kode emisi karbon
        $kodeEmisi = 'EMC-' . Str::random(6);

        EmisiCarbon::create([
            'kode_emisi_karbon' => $kodeEmisi,
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'tanggal_emisi' => $request->tanggal_emisi,
            'kadar_emisi_karbon' => $request->kadar_emisi_karbon,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
            'kode_user' => Auth::guard('pengguna')->user()->kode_user
        ]);

        return redirect()->route('emisicarbon.index')
                        ->with('success', 'Data emisi karbon berhasil ditambahkan.');
    }

    public function edit($kode_emisi_karbon)
    {
        $emisiCarbon = EmisiCarbon::where('kode_emisi_karbon', $kode_emisi_karbon)
                                 ->where('kode_user', Auth::guard('pengguna')->user()->kode_user)
                                 ->firstOrFail();
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

        $emisiCarbon = EmisiCarbon::where('kode_emisi_karbon', $kode_emisi_karbon)
                                 ->where('kode_user', Auth::guard('pengguna')->user()->kode_user)
                                 ->firstOrFail();
        
        $emisiCarbon->update([
            'tanggal_emisi' => $request->tanggal_emisi,
            'kategori_emisi_karbon' => $request->kategori_emisi_karbon,
            'kadar_emisi_karbon' => $request->kadar_emisi_karbon,
            'deskripsi' => $request->deskripsi
        ]);

        return redirect()->route('emisicarbon.index')
                        ->with('success', 'Data emisi karbon berhasil diperbarui.');
    }

    public function destroy($kode_emisi_karbon)
    {
        $emisiCarbon = EmisiCarbon::where('kode_emisi_karbon', $kode_emisi_karbon)
                                 ->where('kode_user', Auth::guard('pengguna')->user()->kode_user)
                                 ->firstOrFail();
        
        $emisiCarbon->delete();

        return redirect()->route('emisicarbon.index')
                        ->with('success', 'Data emisi karbon berhasil dihapus.');
    }
}
