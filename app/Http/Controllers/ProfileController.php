<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        if (Auth::guard('pengguna')->check()) {
            $user = Auth::guard('pengguna')->user();
            return view('profile.user', compact('user'));
        } elseif (Auth::guard('manager')->check()) {
            $user = Auth::guard('manager')->user();
            return view('profile.manager', compact('user'));
        } elseif (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            return view('profile.admin', compact('user'));
        }
    }

    public function update(Request $request)
    {
        $guard = $this->getGuard();
        $user = Auth::guard($guard)->user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique($this->getTable())->ignore($user->id)],
            'no_telepon' => 'required|string|max:15',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $table = $this->getTable();
        $namaColumn = match($guard) {
            'pengguna' => 'nama_user',
            'admin' => 'nama_admin',
            'manager' => 'nama_manager',
            default => 'nama_user'
        };

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }
        }

        try {
            DB::beginTransaction();

            DB::update("UPDATE {$table} SET 
                {$namaColumn} = ?,
                email = ?,
                no_telepon = ?
                WHERE id = ?", 
                [
                    $request->nama,
                    $request->email,
                    $request->no_telepon,
                    $user->id
                ]
            );

            if ($request->filled('new_password')) {
                DB::update("UPDATE {$table} SET 
                    password = ?
                    WHERE id = ?",
                    [
                        Hash::make($request->new_password),
                        $user->id
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Profil berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil');
        }
    }

    private function getGuard()
    {
        if (Auth::guard('pengguna')->check()) return 'pengguna';
        if (Auth::guard('manager')->check()) return 'manager';
        if (Auth::guard('admin')->check()) return 'admin';
    }

    private function getTable()
    {
        if (Auth::guard('pengguna')->check()) return 'penggunas';
        if (Auth::guard('manager')->check()) return 'managers';
        if (Auth::guard('admin')->check()) return 'admins';
    }
} 