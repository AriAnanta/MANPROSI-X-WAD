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
        $kodeManager = Auth::guard('manager')->user()->kode_manager;
        
        $comments = DB::select("
            SELECT c.*, 
                   DATE_FORMAT(c.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                   pcc.tanggal_pembelian_carbon_credit,
                   pcc.jumlah_kompensasi, 
                   pcc.deskripsi
            FROM comments c
            LEFT JOIN pembelian_carbon_credits pcc 
                ON c.kode_pembelian_carbon_credit = pcc.kode_pembelian_carbon_credit
            WHERE c.kode_manager = ?
            ORDER BY c.created_at DESC
            LIMIT 10",
            [$kodeManager]
        );
        
        return view('manager.comments.index', compact('comments'));
    }

    public function create()
    {
        $pembelianList = DB::select("
            SELECT kode_pembelian_carbon_credit,
                   tanggal_pembelian_carbon_credit,
                   jumlah_kompensasi,
                   deskripsi
            FROM pembelian_carbon_credits
            ORDER BY kode_pembelian_carbon_credit DESC"
        );

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

    public function edit($commentId)
    {
        $comment = DB::selectOne("
            SELECT c.*, pcc.tanggal_pembelian_carbon_credit
            FROM comments c
            LEFT JOIN pembelian_carbon_credits pcc 
                ON c.kode_pembelian_carbon_credit = pcc.kode_pembelian_carbon_credit
            WHERE c.id = ?", 
            [$commentId]
        );
        
        if (!$comment) {
            abort(404);
        }

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

    public function destroy($commentId)
    {
        DB::delete("
            DELETE FROM comments 
            WHERE id = ?", 
            [$commentId]
        );
        
        return redirect()->route('manager.comments.index')
            ->with('success', 'Komentar berhasil dihapus');
    }
} 