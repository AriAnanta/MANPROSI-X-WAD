<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Pengguna;

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

        $credentials = $request->only('email', 'password');
        $guard = $request->role;

        if (Auth::guard($guard)->attempt($credentials)) {
            return redirect()->intended($this->redirectTo($guard));
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
} 