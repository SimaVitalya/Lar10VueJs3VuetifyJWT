<?php

use App\Http\Controllers\GetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get', [GetController::class, 'index']);
//});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('/me', [\App\Http\Controllers\AuthController::class, 'me']);
    // Route::group(['middleware' => 'jwt.auth'], function () {
    //     Route::get('/comments', [\App\Http\Controllers\CommentController::class, 'index']);

    // });если нужно скрыть он неавторизированных польховатеей



});
Route::group(['namespace' => 'User', 'prefix' => 'users'], function () {
    Route::post('/', [GetController::class, 'index']);

});
Route::get('/comments', [\App\Http\Controllers\CommentController::class, 'index']);
Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store']);
Route::post('/comments{commentId}/replies', [\App\Http\Controllers\CommentController::class, 'storeReply']);
Route::get('/api/comments/sorted', 'CommentController@sorted');


