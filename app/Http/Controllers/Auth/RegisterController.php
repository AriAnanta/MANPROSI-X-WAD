<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Manager;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showPenggunaRegisterForm()
    {
        return view('auth.register.pengguna');
    }

    public function showAdminRegisterForm()
    {
        return view('auth.register.admin');
    }

    public function showManagerRegisterForm()
    {
        return view('auth.register.manager');
    }

    public function registerPengguna(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        try {
            Pengguna::create([
                'kode_user' => 'USR-' . Str::random(6),
                'nama_user' => $request->nama_user,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telepon' => $request->no_telepon
            ]);

            return redirect()->route('login')
                           ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        try {
            Admin::create([
                'kode_admin' => 'ADM-' . Str::random(6),
                'nama_admin' => $request->nama_admin,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telepon' => $request->no_telepon
            ]);

            return redirect()->route('login')
                           ->with('success', 'Registrasi admin berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }

    public function registerManager(Request $request)
    {
        $request->validate([
            'nama_manager' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        try {
            Manager::create([
                'kode_manager' => 'MGR-' . Str::random(6),
                'nama_manager' => $request->nama_manager,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telepon' => $request->no_telepon
            ]);

            return redirect()->route('login')
                           ->with('success', 'Registrasi manager berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }
} 