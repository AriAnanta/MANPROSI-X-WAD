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

        // Ambil data emisi terbaru dengan relasi pengguna
        $recentEmissions = EmisiCarbon::with('pengguna')
            ->whereNotNull('kode_user')  // Pastikan kode_user tidak null
            ->latest()
            ->take(5)
            ->get();
        
        // Debug untuk memeriksa data
        Log::info('Recent Emissions:', ['emissions' => $recentEmissions->toArray()]);

        // Siapkan data untuk grafik bulanan
        $chartData = $this->prepareChartData();

        Log::info('Data Emisi per Tahun:', ['data' => $recentEmissions]);

        return view('pages.user.dashboard', compact(
            'emisiPerKategori',
            'emisiPending',
            'recentEmissions',
            'chartData'
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
        // Cek guard yang aktif
        if (Auth::guard('pengguna')->check()) {
            // Untuk user biasa, tampilkan data per user
            $user = Auth::guard('pengguna')->user();
            
            // Ambil data 6 bulan terakhir
            $months = collect(range(5, 0))->map(function ($i) {
                return now()->startOfMonth()->subMonths($i);
            });

            $emissions = EmisiCarbon::selectRaw('DATE_FORMAT(tanggal_emisi, "%Y-%m") as month, SUM(kadar_emisi_karbon) as total')
                ->where('kode_user', $user->kode_user)
                ->whereYear('tanggal_emisi', '>=', now()->subMonths(6)->year)
                ->whereMonth('tanggal_emisi', '>=', now()->subMonths(6)->month)
                ->groupBy('month')
                ->get()
                ->keyBy('month');
        } else {
            // Untuk admin, tampilkan data keseluruhan
            $months = collect(range(5, 0))->map(function ($i) {
                return now()->startOfMonth()->subMonths($i);
            });

            $emissions = EmisiCarbon::selectRaw('DATE_FORMAT(tanggal_emisi, "%Y-%m") as month, SUM(kadar_emisi_karbon) as total')
                ->whereYear('tanggal_emisi', '>=', now()->subMonths(6)->year)
                ->whereMonth('tanggal_emisi', '>=', now()->subMonths(6)->month)
                ->groupBy('month')
                ->get()
                ->keyBy('month');
        }
        
        $labels = $months->map(fn ($date) => $date->isoFormat('MMMM Y')); 
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
        // Hitung total pengguna
        $totalPengguna = Pengguna::count();
        
        // Hitung total emisi
        $totalEmisi = EmisiCarbon::where('status', 'approved')->sum('kadar_emisi_karbon');
        
        // Hitung rata-rata emisi per pengguna
        $rataRataEmisi = $totalPengguna > 0 ? $totalEmisi / $totalPengguna : 0;
        
        // Hitung total emisi pending
        $totalEmisiPending = EmisiCarbon::where('status', 'pending')->count();
        
        // Ambil data emisi pending terbaru
        $emisiPending = EmisiCarbon::with('pengguna')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        
        // Ambil top 10 pengguna dengan emisi tertinggi
        $topPengguna = Pengguna::select('penggunas.*')
            ->selectRaw('SUM(emisi_carbons.kadar_emisi_karbon) as total_emisi')
            ->selectRaw('COUNT(emisi_carbons.id) as jumlah_pengajuan')
            ->leftJoin('emisi_carbons', 'penggunas.kode_user', '=', 'emisi_carbons.kode_user')
            ->where('emisi_carbons.status', 'approved')
            ->groupBy('penggunas.kode_user')
            ->orderBy('total_emisi', 'desc')
            ->take(10)
            ->get();
        
        // Siapkan data untuk grafik bulanan
        $chartData = $this->prepareManagerChartData();

        return view('pages.manager.dashboard', compact(
            'totalPengguna',
            'totalEmisi',
            'rataRataEmisi',
            'totalEmisiPending',
            'emisiPending',
            'topPengguna',
            'chartData'
        ));
    }

    private function prepareManagerChartData()
    {
        // Ambil data 6 bulan terakhir
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->startOfMonth()->subMonths($i);
        });

        $emissions = EmisiCarbon::selectRaw('DATE_FORMAT(tanggal_emisi, "%Y-%m") as month, SUM(kadar_emisi_karbon) as total')
            ->where('status', 'approved')
            ->whereYear('tanggal_emisi', '>=', now()->subMonths(6)->year)
            ->whereMonth('tanggal_emisi', '>=', now()->subMonths(6)->month)
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        $labels = $months->map(fn ($date) => $date->isoFormat('MMMM Y'));
        $data = $months->map(function ($date) use ($emissions) {
            $month = $date->format('Y-m');
            return $emissions->get($month)->total ?? 0;
        });

        return [
            'labels' => $labels->values(),
            'data' => $data->values(),
        ];
    }

    public function index()
    {
        $user = Auth::guard('pengguna')->user();
        // Mengambil data emisi karbon per bulan
        $emisiPerBulan = EmisiCarbon::selectRaw('DATE_FORMAT(tanggal_emisi, "%Y-%m") as month, SUM(kadar_emisi_karbon) as total')
        ->where('kode_user', $user->kode_user) // Filter berdasarkan user
        ->whereYear('tanggal_emisi', '>=', now()->subMonths(6)->year)
        ->whereMonth('tanggal_emisi', '>=', now()->subMonths(6)->month)
        ->groupBy('month')
        ->get()
        ->keyBy('month');

        // Menyiapkan data untuk chart
        $labels = [];
        $data = [];
        
        foreach ($emisiPerBulan as $emisi) {
            $bulan = Carbon::create()->month($emisi->bulan)->locale('id');
            $labels[] = $bulan->isoFormat('MMMM');
            $data[] = $emisi->total_emisi;
        }

        $chartData = [
            'labels' => $labels,
            'data' => $data
        ];

        return view('pages.user.dashboard', compact('chartData'));
    }
}
