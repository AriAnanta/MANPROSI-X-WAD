<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = DB::select("
            SELECT *,
                   DATE_FORMAT(created_at, '%d/%m/%Y') as formatted_date
            FROM penggunas
            ORDER BY created_at DESC
            LIMIT 10
        ");
        
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

        DB::insert("
            INSERT INTO penggunas (
                kode_user,
                nama_user,
                email,
                password,
                no_telepon,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
            [
                'USR' . Str::random(8),
                $request->nama_user,
                $request->email,
                Hash::make($request->password),
                $request->no_telepon
            ]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = DB::selectOne("
            SELECT * FROM penggunas WHERE id = ?", 
            [$id]
        );
        
        if (!$user) {
            abort(404);
        }

        return view('pages.admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:penggunas,email,{$id}",
            'no_telepon' => 'required|string|max:15',
            'password' => 'nullable|string|min:8'
        ]);

        if ($request->filled('password')) {
            DB::update("
                UPDATE penggunas 
                SET nama_user = ?,
                    email = ?,
                    password = ?,
                    no_telepon = ?,
                    updated_at = NOW()
                WHERE id = ?",
                [
                    $request->nama_user,
                    $request->email,
                    Hash::make($request->password),
                    $request->no_telepon,
                    $id
                ]
            );
        } else {
            DB::update("
                UPDATE penggunas 
                SET nama_user = ?,
                    email = ?,
                    no_telepon = ?,
                    updated_at = NOW()
                WHERE id = ?",
                [
                    $request->nama_user,
                    $request->email,
                    $request->no_telepon,
                    $id
                ]
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM penggunas WHERE id = ?", [$id]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
} 