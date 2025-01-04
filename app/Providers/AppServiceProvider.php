<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Composer for user layout
        View::composer('layouts.user', function ($view) {
            if (auth()->guard('pengguna')->check()) {
                $userKode = auth()->guard('pengguna')->user()->kode_user;

                $notifications = DB::select("
                    SELECT * FROM notifikasis 
                    WHERE kode_user = ? OR kode_user IS NULL 
                    ORDER BY tanggal DESC, created_at DESC 
                    LIMIT 10
                ", [$userKode]);

                $unreadNotifications = count($notifications);

                $view->with('notifications', $notifications)
                     ->with('unreadNotifications', $unreadNotifications);
            }
        });

        // Add composer for admin layout
        View::composer('layouts.admin', function ($view) {
            $emissionNotifications = DB::table('emisi_carbons')
                ->join('penggunas', 'emisi_carbons.kode_user', '=', 'penggunas.kode_user')
                ->select(
                    'emisi_carbons.created_at',
                    'penggunas.nama_user as nama_pengguna',
                    'emisi_carbons.kadar_emisi_karbon',
                    DB::raw("'emission' as type"),
                    DB::raw("NULL as jumlah_kompensasi")
                )
                ->orderBy('emisi_carbons.created_at', 'desc');

            $compensationNotifications = DB::table('kompensasi_emisi')
                ->select(
                    'kompensasi_emisi.created_at',
                    DB::raw("NULL as nama_pengguna"),
                    DB::raw("NULL as kadar_emisi_karbon"),
                    DB::raw("'compensation' as type"),
                    'kompensasi_emisi.jumlah_kompensasi'
                )
                ->orderBy('kompensasi_emisi.created_at', 'desc');

            $notifications = $emissionNotifications->union($compensationNotifications)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $formattedNotifications = $notifications->map(function ($notification) {
                if ($notification->type === 'emission') {
                    return [
                        'message' => "Pengguna {$notification->nama_pengguna} menambahkan inputan carbon credit sebesar {$notification->kadar_emisi_karbon} kg CO₂",
                        'created_at' => $notification->created_at,
                    ];
                } else {
                    return [
                        'message' => "Manager mengajukan kompensasi emisi sebesar {$notification->jumlah_kompensasi} kg CO₂",
                        'created_at' => $notification->created_at,
                    ];
                }
            });

            $view->with('adminNotifications', $formattedNotifications)
                 ->with('unreadAdminNotifications', count($notifications));
        });
    }
}