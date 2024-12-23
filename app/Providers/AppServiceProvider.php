<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notifikasi;

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
        View::composer('layouts.user', function ($view) {
            if (auth()->guard('pengguna')->check()) {
                $userKode = auth()->guard('pengguna')->user()->kode_user;
                
                $notifications = Notifikasi::where(function($query) use ($userKode) {
                    $query->where('kode_user', $userKode)
                          ->orWhereNull('kode_user');
                })
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(10)  // Limit to last 10 notifications
                ->get();

                $unreadNotifications = $notifications->count(); // You can implement a read/unread system later

                $view->with('notifications', $notifications)
                     ->with('unreadNotifications', $unreadNotifications);
            }
        });
    }
}
