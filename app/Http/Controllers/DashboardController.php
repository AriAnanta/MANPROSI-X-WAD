<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        if (!Auth::guard('pengguna')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('pengguna')->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $kodeUser = $user->kode_user;
        
        // Query untuk mendapatkan data per sub kategori
        $emisiPerSubKategori = DB::select("
            SELECT 
                kategori_emisi_karbon,
                sub_kategori,
                COUNT(*) as jumlah_pengajuan,
                SUM(CASE WHEN status = 'approved' THEN kadar_emisi_karbon ELSE 0 END) as total_emisi,
                MAX(created_at) as last_update
            FROM emisi_carbons
            WHERE kode_user = ?
            GROUP BY kategori_emisi_karbon, sub_kategori
            ORDER BY kategori_emisi_karbon, sub_kategori",
            [$kodeUser]
        );

        // Kelompokkan data berdasarkan kategori dengan cara yang benar
        $groupedEmisi = [];
        foreach ($emisiPerSubKategori as $emisi) {
            $kategori = strtolower($emisi->kategori_emisi_karbon);
            if (!isset($groupedEmisi[$kategori])) {
                $groupedEmisi[$kategori] = [];
            }
            $groupedEmisi[$kategori][] = [
                'sub_kategori' => $emisi->sub_kategori,
                'jumlah_pengajuan' => $emisi->jumlah_pengajuan,
                'total_emisi' => $emisi->total_emisi,
                'last_update' => $emisi->last_update
            ];
        }

        // Query untuk mendapatkan total emisi per kategori
        $emisiPerKategori = DB::select("
            SELECT 
                kategori_emisi_karbon,
                SUM(kadar_emisi_karbon) as total_emisi,
                COUNT(*) as jumlah_pengajuan,
                MAX(updated_at) as last_update
            FROM emisi_carbons 
            WHERE kode_user = ? 
            AND status = 'approved'
            GROUP BY kategori_emisi_karbon",
            [$kodeUser]
        );

        // Hitung total emisi yang sudah disetujui
        $totalEmisiApprovedTon = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon), 0) / 1000 as total
            FROM emisi_carbons 
            WHERE kode_user = ? 
            AND status = 'approved'",
            [$kodeUser]
        )->total;

      
        $chartData = $this->getChartData(kodeUser: $kodeUser);

        
        $emisiPending = DB::select("
            SELECT kategori_emisi_karbon, kadar_emisi_karbon, tanggal_emisi
            FROM emisi_carbons 
            WHERE kode_user = ? 
            AND status = 'pending'
            ORDER BY tanggal_emisi DESC 
            LIMIT 5",
            [$kodeUser]
        );

        return view('pages.user.dashboard', [
            'emisiPerKategori' => $emisiPerKategori,
            'emisiPerSubKategori' => $groupedEmisi,
            'totalEmisiApprovedTon' => $totalEmisiApprovedTon,
            'chartData' => $chartData,
            'emisiPending' => $emisiPending
        ]);
    }

    public function adminDashboard()
    {
      
        $totalUsers = DB::selectOne("SELECT COUNT(*) as total FROM penggunas")->total;
      
        $totalEmissionsApprovedTon = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon) / 1000, 0) as total_ton
            FROM emisi_carbons
            WHERE status = 'approved'"
        )->total_ton;
        
        
        $averageEmissionPerUser = $totalUsers > 0 ? round($totalEmissionsApprovedTon / $totalUsers, 2) : 0;
        
      
        $recentEmissions = DB::select("
            SELECT e.*, p.nama_user
            FROM emisi_carbons e
            JOIN penggunas p ON e.kode_user = p.kode_user
            WHERE e.kode_user IS NOT NULL
            ORDER BY e.created_at DESC
            LIMIT 5"
        );
      
        $chartData = $this->prepareChartData();
        
        return view('pages.admin.dashboard', compact(
            'totalUsers',
            'totalEmissionsApprovedTon',
            'averageEmissionPerUser',
            'recentEmissions',
            'chartData'
        ));
    }

    private function prepareChartData($kodeUser = null)
    {
        $monthlyData = DB::select("
            SELECT 
                DATE_FORMAT(tanggal_emisi, '%Y-%m') as month,
                SUM(kadar_emisi_karbon) as total_emisi
            FROM emisi_carbons
            WHERE status = 'approved'
            " . ($kodeUser ? "AND kode_user = ?" : "") . "
            GROUP BY DATE_FORMAT(tanggal_emisi, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12",
            $kodeUser ? [$kodeUser] : []
        );

        return [
            'labels' => array_column($monthlyData, 'month'),
            'data' => array_column($monthlyData, 'total_emisi')
        ];
    }

    private function getChartData($kodeUser)
    {
        $monthlyData = DB::select("
            SELECT 
                DATE_FORMAT(tanggal_emisi, '%Y-%m') as month,
                SUM(kadar_emisi_karbon) as total_emisi
            FROM emisi_carbons
            WHERE kode_user = ?
            AND status = 'approved'
            GROUP BY DATE_FORMAT(tanggal_emisi, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12",
            [$kodeUser]
        );

        return [
            'labels' => array_column($monthlyData, 'month'),
            'data' => array_column($monthlyData, 'total_emisi')
        ];
    }

    public function managerDashboard()
    {
      
        $totalPengguna = DB::selectOne("SELECT COUNT(*) as total FROM penggunas")->total;
        
       
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
        
        // Hitung total emisi approved dalam ton CO2e
        $totalEmisiApprovedTon = DB::selectOne("
            SELECT COALESCE(SUM(kadar_emisi_karbon) / 1000, 0) as total_ton
            FROM emisi_carbons
            WHERE status = 'approved'
            AND YEAR(tanggal_emisi) = YEAR(CURRENT_DATE())"
        )->total_ton;
        
        // Siapkan data untuk grafik bulanan
        $chartData = $this->prepareManagerChartData();

        // Hitung total emisi yang sudah dikompensasi
        $totalKompensasi = DB::selectOne("
            SELECT COALESCE(SUM(jumlah_kompensasi) / 1000, 0) as total_ton
            FROM kompensasi_emisi
            WHERE status = 'completed'"
        )->total_ton;

        // Hitung persentase kompensasi
        $persentaseKompensasi = $totalEmisiApprovedTon > 0 
            ? round(($totalKompensasi / $totalEmisiApprovedTon) * 100, 2)
            : 0;

        return view('pages.manager.dashboard', compact(
            'totalPengguna',
            'totalEmisiPerTahun',
            'rataRataEmisiPerTahun',
            'totalEmisiPending',
            'emisiPending',
            'topPengguna',
            'totalEmisiPerTahunPending',
            'totalEmisiApprovedTon',
            'chartData',
            'totalKompensasi',
            'persentaseKompensasi'
        ));
    }

    // Tambahkan method baru untuk data grafik manager
    private function prepareManagerChartData()
    {
        $monthlyData = DB::select("
            SELECT 
                DATE_FORMAT(tanggal_emisi, '%Y-%m') as month,
                SUM(kadar_emisi_karbon) as total_emisi
            FROM emisi_carbons
            WHERE status = 'approved'
            GROUP BY DATE_FORMAT(tanggal_emisi, '%Y-%m')
            ORDER BY month DESC
            LIMIT 12"
        );

        return [
            'labels' => array_column($monthlyData, 'month'),
            'data' => array_column($monthlyData, 'total_emisi')
        ];
    }
}