<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = Pengguna::latest()->paginate(10);
        return view('pages.admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas',
            'password' => 'required|string|min:8',
            'no_telepon' => 'required|string|max:15'
        ]);

        Pengguna::create([
            'kode_user' => 'USR' . Str::random(8),
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = Pengguna::findOrFail($id);
        return view('pages.admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Pengguna::findOrFail($id);

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas,email,'.$id,
            'no_telepon' => 'required|string|max:15',
            'password' => 'nullable|string|min:8'
        ]);

        $userData = [
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = Pengguna::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
} 