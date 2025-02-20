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
        try {
            $request->validate([
                'content' => 'required|string',
            ]);

            $comment = Comments::create([
                'content' => $request->content,
                'news_id' => $newsId,
                'user_id' => Auth::id(),
            ]);

            dispatch(new \App\Jobs\ProcessComment($comment->id));

            return response()->json(['message' => 'Comment queued for processing'], 202);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process comment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
