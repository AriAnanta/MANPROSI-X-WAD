<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmisiCarbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::guard('pengguna')->user();
        
        // Mengambil total emisi per kategori
        $emisiPerKategori = EmisiCarbon::where('kode_user', $user->kode_user)
            ->select('kategori_emisi_karbon', DB::raw('SUM(kadar_emisi_karbon) as total_emisi'))
            ->groupBy('kategori_emisi_karbon')
            ->get();

        // Mengambil emisi dengan status pending
        $emisiPending = EmisiCarbon::where('kode_user', $user->kode_user)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data untuk grafik berdasarkan tahun
        $emisiPerTahun = EmisiCarbon::where('kode_user', $user->kode_user)
            ->select(
                DB::raw('YEAR(tanggal_emisi) as tahun'),
                DB::raw('SUM(kadar_emisi_karbon) as total_emisi')
            )
            ->groupBy(DB::raw('YEAR(tanggal_emisi)'))
            ->orderBy('tahun', 'asc')
            ->get();

        Log::info('Data Emisi per Tahun:', ['data' => $emisiPerTahun]);

        return view('pages.user.dashboard', compact(
            'emisiPerKategori',
            'emisiPending',
            'emisiPerTahun'
        ));
    }

    public function adminDashboard()
    {
        return view('pages.admin.dashboard');
    }

    public function managerDashboard()
    {
        return view('pages.manager.dashboard');
    }
}
