<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleFileAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Cek jika request ke bukti-pembelian
        if ($request->is('bukti-pembelian/*') && !Auth::guard('admin')->check()) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
} 