<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comments;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{
    public function index(StoreRequest $request)
    {
        $comments = Comments::all();
           return response()->json($comments);
    }

//    public function allComments($productId)
//    {
//        $product = Product::findOrFail($productId);
//
//        $comments = $product->comments()->orderBy('created_at', 'desc')->with('user')->get();
//        return response()->json($comments);
//    }
//    public function storeComment($productId, CommentRequest $request)
//    {
//        $product = Product::findOrFail($productId);
//
//        $user = JWTAuth::user();
//        $comment = $product->comments()->create([
//            'message' => $request->input('message'),
//            'rating' => $request->input('rating'),
//            'user_id' => $user->id,
//        ]);
//
//        return response()->json($comment);
//    }

}
