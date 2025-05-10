<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function storeNewComment(Request $request) {
        $request->validate([
            'body' => 'required',
            'post_id' => 'required'
        ]);

        Comment::create([
            'body' => $request->body,
            'post_id' => $request->post_id,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'تم إضافة تعليقك بنجاح');
    }
}
