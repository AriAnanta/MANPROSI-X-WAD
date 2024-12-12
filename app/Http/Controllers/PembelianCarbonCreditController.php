<?php

namespace App\Http\Controllers;

use App\Models\PembelianCarbonCredit;
use Illuminate\Http\Request;

class PembelianCarbonCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carbon_credit = PembelianCarbonCredit::all();
        return view('carbon_credit.index', compact('carbon_credit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('carbon_credir.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'kode_pembelian_carbon_credit' => 'required|string|max:5|unique:pembelian_carbon_credit',
            'jumlah_pembelian_carbon_credit' => 'required|integer',
            'tanggal_pembelian_carbon_credit' => 'required|date',
            'bukti_pembelian' => 'required|string',
            'deskripsi' => 'required|string'
        ]);

        PembelianCarbonCredit::create($validateData);
        return redirect()->route('carbon_credit.index')->with('success', 'Data Pembelian Carbon Credit Ditambahkan');
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
            'kode_pembelian_carbon_credit'=>'required|unique:pembelian_carbon_credit,kode_pembelian_carbon_credit,'.$carbon_credit->id,
            'jumlah_pembelian_carbon_credit'=>'required|integer',
            'deskripsi'=>'required'
        ]);
        $carbon_credit->update($request->all());
        return redirect()->route('carbon_credit.index')->with('success','Data Pembelian Carbon Credit Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PembelianCarbonCredit $pembelianCarbonCredit)
    {
        $carbon_credit = PembelianCarbonCredit::findOrFail($pembelianCarbonCredit);
        $carbon_credit->delete();
        return redirect()->route('carbon_credit.index')->with('success','Data Pembelian Carbon Credit Dihapus');
    }
}
