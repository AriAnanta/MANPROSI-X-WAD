<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = DB::select("
            SELECT c.*, 
                   m.nama_manager as manager_name,
                   DATE_FORMAT(c.created_at, '%d/%m/%Y %H:%i') as formatted_date,
                   pcc.tanggal_pembelian_carbon_credit,
                   pcc.jumlah_kompensasi
            FROM comments c
            LEFT JOIN managers m ON c.kode_manager = m.kode_manager
            LEFT JOIN pembelian_carbon_credits pcc 
                ON c.kode_pembelian_carbon_credit = pcc.kode_pembelian_carbon_credit
            ORDER BY c.created_at DESC
            LIMIT 10
        ");
        
        return view('admin.comments.index', compact('comments'));
    }

    public function markAsRead($commentId)
    {
        DB::update("
            UPDATE comments 
            SET status = 'read',
                updated_at = NOW()
            WHERE id = ?", 
            [$commentId]
        );
        
        return redirect()->back()->with('success', 'Komentar telah ditandai sebagai dibaca');
    }

    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        DB::update("
            UPDATE comments 
            SET admin_reply = ?,
                manager_read = false,
                updated_at = NOW()
            WHERE id = ?",
            [$request->reply, $commentId]
        );

        return redirect()->back()->with('success', 'Balasan berhasil dikirim');
    }
} 