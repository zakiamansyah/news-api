<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $newsId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comments([
            'content' => $request->content,
            'news_id' => $newsId,
            'user_id' => Auth::id(),
        ]);

        dispatch(new \App\Jobs\ProcessComment($comment));

        return response()->json(['message' => 'Comment queued for processing'], 202);
    }
}
