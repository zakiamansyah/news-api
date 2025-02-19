<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        return NewsResource::collection(News::paginate(10));
    }

    public function store(Request $request)
    {
        if (!Auth::user() || !Auth::user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('news', 'public');

        $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $path,
            'user_id' => Auth::id(),
        ]);

        Logs::create(['action' => 'created', 'news_id' => $news->id, 'user_id' => Auth::id()]);

        return new NewsResource($news);
    }
}
