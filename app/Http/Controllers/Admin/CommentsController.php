<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['manager', 'pembelianCarbonCredit'])
                         ->latest()
                         ->paginate(10);
        
        return view('admin.comments.index', compact('comments'));
    }

    public function markAsRead(Comment $comment)
    {
        $comment->update(['status' => 'read']);
        return redirect()->back()->with('success', 'Komentar telah ditandai sebagai dibaca');
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'reply' => 'required|string'
        ]);

        $comment->update([
            'admin_reply' => $request->reply,
            'manager_read' => false
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil dikirim');
    }
} 