<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comments;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class CommentController extends Controller
{
    public function index()
    {
        // $comments = Comments::get();
        $comments = Comments::with('user')->get(); // Загружаем связанную модель пользователя
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        $user = JWTAuth::user();
        //что бы JWT работал нужно добавить на фронт это       const token = localStorage.getItem('access_token'); и конфиг headers: {
        //          'Authorization': `Bearer ${token}`,
        //          'Content-Type': 'application/json'
        //        }
        $comment = new Comments();
        $comment->content = $validatedData['content'];
        $comment->user_id = $user->id;
        $comment->save();

        $comment->load('user'); // Загружаем связанную модель пользователя

        return response()->json($comment, 201);
    }

    public function storeReply(Request $request, $commentId)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);
        $user = JWTAuth::user();

        $comment = Comments::findOrFail($commentId);

        $reply = new Reply($validatedData);
        $reply->user_id = $user->id;
        $comment->replies()->save($reply);

        $reply->load('user');

        return response()->json($reply, 201);
    }

}
