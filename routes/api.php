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


Route::get('/post', [\App\Http\Controllers\PostController::class, 'index']);
Route::post('/post', [\App\Http\Controllers\PostController::class, 'index']);
Route::post('/post/store', [\App\Http\Controllers\PostController::class, 'store']);
Route::put('/post/update/{id}', [\App\Http\Controllers\PostController::class, 'update']);
Route::delete('/post/delete/{id}', [\App\Http\Controllers\PostController::class, 'destroy']);


Route::get('/parse',[\App\Http\Controllers\ParseController::class,'index']);



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



