<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,manager,pengguna'
        ]);

        $table = $request->role . 's';
        if ($request->role === 'pengguna') {
            $table = 'penggunas';
        }

        $user = DB::selectOne("
            SELECT * FROM {$table} 
            WHERE email = ?", 
            [$request->email]
        );

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard($request->role)->loginUsingId($user->id);
            return redirect()->intended($this->redirectTo($request->role));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ]);
    }

    protected function redirectTo($guard)
    {
        switch ($guard) {
            case 'admin':
                return '/admin/dashboard';
            case 'manager':
                return '/manager/dashboard';
            default:
                return '/dashboard';
        }
    }

    public function logout(Request $request)
    {
        $guard = Auth::getDefaultDriver();
        Auth::guard($guard)->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    protected function authenticated(Request $request, $user)
    {
        Log::info('User authenticated', [
            'user' => $user,
            'admin_check' => Auth::guard('admin')->check(),
            'pengguna_check' => Auth::guard('pengguna')->check(),
            'manager_check' => Auth::guard('manager')->check()
        ]);
        
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::guard('pengguna')->check()) {
            return redirect()->route('user.dashboard');
        } elseif (Auth::guard('manager')->check()) {
            return redirect()->route('manager.dashboard');
        }
    }
} 