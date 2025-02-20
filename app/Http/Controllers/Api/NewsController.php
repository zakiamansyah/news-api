<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        return NewsResource::collection(News::paginate(10));
    }

    public function store(Request $request)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
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

            Logs::create([
                'action' => 'created',
                'news_id' => $news->id,
                'user_id' => Auth::id()
            ]);

            return response()->json(new NewsResource($news), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $news = News::with('comments')->findOrFail($id);
            return new NewsResource($news);
        } catch (\Exception $e) {
            return response()->json(['message' => 'News not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $news = News::findOrFail($id);

            \Log::info('Incoming update request:', $request->all());

            $request->validate([
                'title' => 'sometimes|string',
                'content' => 'sometimes|string',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->has('title')) {
                \Log::info('Updating title:', ['title' => $request->title]);
                $news->title = $request->title;
            }
            if ($request->has('content')) {
                $news->content = $request->content;
            }

            if ($request->hasFile('image')) {
                if ($news->image_path) {
                    Storage::disk('public')->delete($news->image_path);
                }
                $path = $request->file('image')->store('news', 'public');
                $news->image_path = $path;
            }

            $news->save();

            Logs::create(['action' => 'updated', 'news_id' => $news->id, 'user_id' => Auth::id()]);

            return new NewsResource($news);
        } catch (\Exception $e) {
            \Log::error('Update failed:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'News not found or unauthorized'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            
            $news = News::findOrFail($id);

            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }

            $news->delete();

            Logs::create(['action' => 'deleted', 'news_id' => $id, 'user_id' => Auth::id()]);

            return response()->json(['message' => 'News deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'News not found or unauthorized'], 404);
        }
    }
}
