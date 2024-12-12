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

        $pengguna = Pengguna::create([
            'kode_user' => 'USR-' . Str::random(6),
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon
        ]);

        auth('pengguna')->login($pengguna);

        return redirect()->route('user.dashboard');
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'nama_admin' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        $admin = Admin::create([
            'kode_admin' => 'ADM-' . Str::random(6),
            'nama_admin' => $request->nama_admin,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon
        ]);

        auth('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }

    public function registerManager(Request $request)
    {
        $request->validate([
            'nama_manager' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:managers',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:15'
        ]);

        $manager = Manager::create([
            'kode_manager' => 'MGR-' . Str::random(6),
            'nama_manager' => $request->nama_manager,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon
        ]);

        auth('manager')->login($manager);

        return redirect()->route('manager.dashboard');
    }
} 