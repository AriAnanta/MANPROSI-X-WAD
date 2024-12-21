<?php

namespace App\Http\Controllers;

use App\Models\PembelianCarbonCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PembelianCarbonCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carbon_credit = PembelianCarbonCredit::where('kode_admin', Auth::guard('admin')->user()->kode_admin)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        return view('carbon_credit.index', compact('carbon_credit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('carbon_credit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pembelian_carbon_credit' => 'required|date',
            'bukti_pembelian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'jumlah_pembelian_carbon_credit' => 'required|numeric|min:0',
            'deskripsi' => 'required|string'
        ]);

        // Generate kode emisi karbon
        $kodePembelian = 'PCC-' . Str::random(6);

        // Handle file upload
        $buktiPembelianPath = null;
        if ($request->hasFile('bukti_pembelian')) {
            $file = $request->file('bukti_pembelian');
            $fileName = $kodePembelian . '-' . time() . '.' . $file->getClientOriginalExtension();
            $buktiPembelianPath = $file->storeAs('bukti-pembelian', $fileName, 'public');
        }

        PembelianCarbonCredit::create([
            'kode_pembelian_carbon_credit' => $kodePembelian,
            'jumlah_pembelian_carbon_credit' => $request->jumlah_pembelian_carbon_credit,
            'tanggal_pembelian_carbon_credit' => $request->tanggal_pembelian_carbon_credit,
            'bukti_pembelian' => $buktiPembelianPath,
            'deskripsi' => $request->deskripsi,
            'kode_admin' => Auth::guard('admin')->user()->kode_admin
        ]);

        return redirect()->route('carbon_credit.index')
                        ->with('success', 'Data pembelian carbon credit berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PembelianCarbonCredit $pembelianCarbonCredit)
    {
        $carbon_credit = PembelianCarbonCredit::findOrFail($pembelianCarbonCredit);
        return view('carbon_credit.create', compact('carbon_credit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PembelianCarbonCredit $pembelianCarbonCredit)
    {
        $carbon_credit = PembelianCarbonCredit::findOrFail($pembelianCarbonCredit);
        return view('carbon_credit.edit', compact('carbon_credit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembelianCarbonCredit $pembelianCarbonCredit)
    {
        $carbon_credit = PembelianCarbonCredit::findOrFail($pembelianCarbonCredit);
        
        $request->validate([
            'kode_pembelian_carbon_credit' => 'required|unique:pembelian_carbon_credits,kode_pembelian_carbon_credit,'.$carbon_credit->id,
            'jumlah_pembelian_carbon_credit' => 'required|numeric',
            'deskripsi' => 'required',
            'bukti_pembelian' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        // Handle file upload jika ada file baru
        if ($request->hasFile('bukti_pembelian')) {
            // Hapus file lama jika ada
            if ($carbon_credit->bukti_pembelian) {
                Storage::disk('public')->delete($carbon_credit->bukti_pembelian);
            }

            // Upload file baru
            $file = $request->file('bukti_pembelian');
            $fileName = $carbon_credit->kode_pembelian_carbon_credit . '-' . time() . '.' . $file->getClientOriginalExtension();
            $data['bukti_pembelian'] = $file->storeAs('bukti-pembelian', $fileName, 'public');
        }

        $carbon_credit->update($data);
        return redirect()->route('carbon_credit.index')->with('success','Data Pembelian Carbon Credit Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembelianCarbonCredit $pembelianCarbonCredit)
    {
        $carbon_credit = PembelianCarbonCredit::findOrFail($pembelianCarbonCredit);
        
        // Hapus file bukti pembelian jika ada
        if ($carbon_credit->bukti_pembelian) {
            Storage::disk('public')->delete($carbon_credit->bukti_pembelian);
        }

        $carbon_credit->delete();
        return redirect()->route('carbon_credit.index')->with('success','Data Pembelian Carbon Credit Dihapus');
    }
}
