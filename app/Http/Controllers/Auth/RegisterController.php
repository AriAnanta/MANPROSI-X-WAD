<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            $kodeUser = 'USR-' . Str::random(6);
            
            DB::insert("
                INSERT INTO penggunas (
                    kode_user, nama_user, email, password, 
                    no_telepon, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeUser,
                    $request->nama_user,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon
                ]
            );

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
            $kodeAdmin = 'ADM-' . Str::random(6);
            
            DB::insert("
                INSERT INTO admins (
                    kode_admin, nama_admin, email, password, 
                    no_telepon, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeAdmin,
                    $request->nama_admin,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon
                ]
            );

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
            $kodeManager = 'MGR-' . Str::random(6);
            
            DB::insert("
                INSERT INTO managers (
                    kode_manager, nama_manager, email, password, 
                    no_telepon, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $kodeManager,
                    $request->nama_manager,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon
                ]
            );

            return redirect()->route('login')
                           ->with('success', 'Registrasi manager berhasil! Silakan login dengan akun Anda.');
        } catch (\Exception $e) {
            return back()->withInput()
                        ->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.']);
        }
    }
} 