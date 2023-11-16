<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    //    public function index()
    //    {
    //        $posts = Post::all();
    //        return response()->json($posts);
    //    }
    public function index(Request $request)
    {
        $orderBy = $request->input('order_by', 'title');
        $sortBy = $request->input('sort_by', 'asc');
        $perPage = $request->input('per_page', 10);
        $searchQuery = $request->input('search_query');
        $query = Post::query();
        if ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%');
        }
        if ($orderBy === 'date') {
            $query->orderBy('created_at', $sortBy);
        } else {
            $query->orderBy($orderBy, $sortBy);
        }
        $posts = $query->paginate($perPage);
        return response()->json([
            'posts' => $posts,

        ]);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
        Post::create($data);
        return response()->json([
            'message' => 'Post created'
        ]);
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validated();
        $post->update($data);
        return response()->json([
            'message' => 'Post updated'
        ]);
    }

    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return response()->json([
            'message' => 'Post deleted'
        ]);
    }
}
