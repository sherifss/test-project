<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/posts', [PostController::class, 'store']);
Route::get('/posts', [PostController::class, 'getAll']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::delete('/posts/{id}', [PostController::class, 'delete']);


Route::get('/comments', [CommentController::class, 'getAll']);
Route::post('/comments', [CommentController::class, 'store']);
Route::get('/comments/{id}', [CommentController::class, 'show']);
Route::delete('/comments/{id}', [CommentController::class, 'delete']);