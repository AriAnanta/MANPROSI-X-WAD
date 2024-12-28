<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\EmisiCarbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);
        Comment::create(['emisi_carbon_id' => $id, 'comment' => $request->comment]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string']);
        $comment = Comment::findOrFail($id);
        $comment->update(['comment' => $request->comment]);

        return redirect()->back()->with('success', 'Komentar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}
