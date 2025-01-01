<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\PembelianCarbonCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::where('kode_manager', Auth::guard('manager')->user()->kode_manager)
                         ->with(['pembelianCarbonCredit'])
                         ->latest()
                         ->paginate(10);
        
        return view('manager.comments.index', compact('comments'));
    }

    public function create()
    {
        $pembelianList = PembelianCarbonCredit::select([
                            'kode_pembelian_carbon_credit',
                            'tanggal_pembelian_carbon_credit',
                            'jumlah_kompensasi',
                            'deskripsi'
                        ])
                        ->orderBy('kode_pembelian_carbon_credit', 'desc')
                        ->get();

        return view('manager.comments.create', compact('pembelianList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'kode_pembelian_carbon_credit' => 'required|exists:pembelian_carbon_credits,kode_pembelian_carbon_credit'
        ]);

        DB::insert("
            INSERT INTO comments (
                kode_pembelian_carbon_credit,
                kode_manager,
                comment,
                status,
                created_at,
                updated_at
            ) VALUES (?, ?, ?, 'unread', NOW(), NOW())",
            [
                $request->kode_pembelian_carbon_credit,
                Auth::guard('manager')->user()->kode_manager,
                $request->comment
            ]
        );

        return redirect()->route('manager.comments.index')
                        ->with('success', 'Komentar berhasil ditambahkan');
    }

    public function edit(Comment $comment)
    {
        return view('manager.comments.edit', compact('comment'));
    }

    public function update(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        DB::update("
            UPDATE comments 
            SET comment = ?,
                status = 'unread',
                updated_at = NOW()
            WHERE id = ?",
            [$request->comment, $commentId]
        );

        return redirect()->route('manager.comments.index')
                        ->with('success', 'Komentar berhasil diperbarui');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('manager.comments.index')
                        ->with('success', 'Komentar berhasil dihapus');
    }
} 