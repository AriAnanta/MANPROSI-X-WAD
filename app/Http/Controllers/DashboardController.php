<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmisiCarbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pengguna;

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
        // Hitung total pengguna
        $totalUsers = Pengguna::count();
        
        // Hitung total emisi
        $totalEmissions = EmisiCarbon::sum('kadar_emisi_karbon');
        
        // Hitung rata-rata emisi per pengguna
        $averageEmissionPerUser = $totalUsers > 0 ? round($totalEmissions / $totalUsers, 2) : 0;
        
        // Ambil data emisi terbaru dengan relasi pengguna
        $recentEmissions = EmisiCarbon::with('pengguna')
            ->whereNotNull('kode_user')  // Pastikan kode_user tidak null
            ->latest()
            ->take(5)
            ->get();
        
        // Debug untuk memeriksa data
        Log::info('Recent Emissions:', ['emissions' => $recentEmissions->toArray()]);
        
        // Siapkan data untuk grafik
        $chartData = $this->prepareChartData();
        
        return view('pages.admin.dashboard', compact(
            'totalUsers',
            'totalEmissions',
            'averageEmissionPerUser',
            'recentEmissions',
            'chartData'
        ));
    }

    private function prepareChartData()
    {
        // Ambil data 6 bulan terakhir
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->startOfMonth()->subMonths($i);
        });

        $emissions = EmisiCarbon::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(kadar_emisi_karbon) as total')
            ->whereYear('created_at', '>=', now()->subMonths(6)->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $labels = $months->map(fn ($date) => $date->format('M Y'));
        $data = $months->map(function ($date) use ($emissions) {
            $month = $date->format('Y-m');
            return $emissions->get($month)->total ?? 0;
        });

        return [
            'labels' => $labels->values(),
            'data' => $data->values(),
        ];
    }

    public function managerDashboard()
    {
        return view('pages.manager.dashboard');
    }
}
