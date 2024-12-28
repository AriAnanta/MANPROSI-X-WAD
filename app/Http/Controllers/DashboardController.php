<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::guard('pengguna')->user();
        
        // Mengambil total emisi per kategori
        $emisiPerKategori = DB::select("
            SELECT kategori_emisi_karbon, SUM(kadar_emisi_karbon) as total_emisi
            FROM emisi_carbons
            WHERE kode_user = ?
            GROUP BY kategori_emisi_karbon",
            [$user->kode_user]
        );

        // Mengambil emisi dengan status pending
        $emisiPending = DB::select("
            SELECT * FROM emisi_carbons
            WHERE kode_user = ?
            AND status = 'pending'
            ORDER BY created_at DESC
            LIMIT 5",
            [$user->kode_user]
        );

        // Ambil data emisi terbaru dengan relasi pengguna
        $recentEmissions = DB::select("
            SELECT e.*, p.nama_user
            FROM emisi_carbons e
            JOIN penggunas p ON e.kode_user = p.kode_user
            WHERE e.kode_user IS NOT NULL
            ORDER BY e.created_at DESC
            LIMIT 5"
        );

        // Siapkan data untuk grafik bulanan
        $chartData = $this->prepareChartData();

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
        $totalUsers = DB::selectOne("SELECT COUNT(*) as total FROM penggunas")->total;
        
        // Hitung total emisi
        $totalEmissions = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon), 0) as total 
            FROM emisi_carbons"
        )->total;
        
        // Hitung rata-rata emisi per pengguna
        $averageEmissionPerUser = $totalUsers > 0 ? round($totalEmissions / $totalUsers, 2) : 0;
        
        // Ambil data emisi terbaru dengan relasi pengguna
        $recentEmissions = DB::select("
            SELECT e.*, p.nama_user
            FROM emisi_carbons e
            JOIN penggunas p ON e.kode_user = p.kode_user
            WHERE e.kode_user IS NOT NULL
            ORDER BY e.created_at DESC
            LIMIT 5"
        );
        
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
        if (Auth::guard('pengguna')->check()) {
            $user = Auth::guard('pengguna')->user();
            
            $emissions = DB::select("
                SELECT DATE_FORMAT(tanggal_emisi, '%Y-%m') as month,
                       SUM(kadar_emisi_karbon) as total
                FROM emisi_carbons
                WHERE kode_user = ?
                AND tanggal_emisi >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(tanggal_emisi, '%Y-%m')",
                [$user->kode_user]
            );
        } else {
            $emissions = DB::select("
                SELECT DATE_FORMAT(tanggal_emisi, '%Y-%m') as month,
                       SUM(kadar_emisi_karbon) as total
                FROM emisi_carbons
                WHERE tanggal_emisi >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(tanggal_emisi, '%Y-%m')"
            );
        }

        // Process data for chart
        $labels = [];
        $data = [];
        foreach ($emissions as $emission) {
            $labels[] = date('F Y', strtotime($emission->month . '-01'));
            $data[] = $emission->total;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function managerDashboard()
    {
        // Hitung total pengguna
        $totalPengguna = DB::selectOne("SELECT COUNT(*) as total FROM penggunas")->total;
        
        // Hitung total emisi per tahun
        $totalEmisiPerTahun = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon), 0) as total
            FROM emisi_carbons
            WHERE status = 'approved'
            AND YEAR(tanggal_emisi) = YEAR(CURRENT_DATE())"
        )->total;

        $totalEmisiPerTahunPending = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon), 0) as total
            FROM emisi_carbons
            WHERE status = 'pending'
            AND YEAR(tanggal_emisi) = YEAR(CURRENT_DATE())"
        )->total;
        
        // Hitung rata-rata emisi per tahun per pengguna
        $rataRataEmisiPerTahun = $totalPengguna > 0 ? $totalEmisiPerTahun / $totalPengguna : 0;
        
        // Hitung total emisi pending
        $totalEmisiPending = DB::selectOne("
            SELECT COUNT(*) as total
            FROM emisi_carbons
            WHERE status = 'pending'"
        )->total;
        
        // Ambil data emisi pending terbaru
        $emisiPending = DB::select("
            SELECT e.*, p.nama_user
            FROM emisi_carbons e
            JOIN penggunas p ON e.kode_user = p.kode_user
            WHERE e.status = 'pending'
            ORDER BY e.created_at DESC
            LIMIT 5"
        );
        
        // Ambil top 10 pengguna dengan emisi tertinggi
        $topPengguna = DB::select("
            SELECT p.*, 
                   SUM(e.kadar_emisi_karbon) as total_emisi,
                   COUNT(e.id) as jumlah_pengajuan
            FROM penggunas p
            LEFT JOIN emisi_carbons e ON p.kode_user = e.kode_user
            WHERE e.status = 'approved'
            GROUP BY p.kode_user
            ORDER BY total_emisi DESC
            LIMIT 10"
        );
        
        // Siapkan data untuk grafik bulanan
        $chartData = $this->prepareChartData();

        return view('pages.manager.dashboard', compact(
            'totalPengguna',
            'totalEmisiPerTahun',
            'rataRataEmisiPerTahun',
            'totalEmisiPending',
            'emisiPending',
            'topPengguna',
            'totalEmisiPerTahunPending',
            'chartData'
        ));
    }
}
