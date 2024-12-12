<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmisiCarbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function userDashboard()
    {
        $user = Auth::guard('pengguna')->user();
        
        // Mengambil total emisi
        $totalEmisi = EmisiCarbon::where('kode_user', $user->kode_user)->sum('kadar_emisi_karbon');
        
        // Mengambil carbon credits
        $carbonCredits = 0; // Implementasikan sesuai logika bisnis Anda
        
        // Status emisi
        $statusEmisi = $this->calculateEmissionStatus($totalEmisi);
        
        // Data untuk grafik
        $chartData = EmisiCarbon::where('kode_user', $user->kode_user)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('M');
            })
            ->map(function($group) {
                return $group->sum('kadar_emisi_karbon');
            });

        // Aktivitas terakhir
        $recentActivities = EmisiCarbon::where('kode_user', $user->kode_user)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($activity) {
                $activity->status_color = $this->getStatusColor($activity->status);
                return $activity;
            });

        return view('pages.user.dashboard', [
            'totalEmisi' => $totalEmisi,
            'carbonCredits' => $carbonCredits,
            'statusEmisi' => $statusEmisi,
            'chartLabels' => $chartData->keys(),
            'chartData' => $chartData->values(),
            'recentActivities' => $recentActivities
        ]);
    }

    private function calculateEmissionStatus($totalEmisi)
    {
        // Implementasikan logika status emisi
        if ($totalEmisi < 1000) {
            return 'Rendah';
        } elseif ($totalEmisi < 5000) {
            return 'Sedang';
        } else {
            return 'Tinggi';
        }
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'completed':
                return 'success';
            case 'pending':
                return 'warning';
            case 'cancelled':
                return 'danger';
            default:
                return 'secondary';
        }
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
