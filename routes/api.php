<?php

use App\Http\Controllers\api\AuthController;
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


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::prefix('/articles')
    ->group(function (){

        Route::get('',[\App\Http\Controllers\ArticleController::class,'index']);
        Route::post('/store',[\App\Http\Controllers\ArticleController::class,'store']);
        Route::post('/{id}',[\App\Http\Controllers\ArticleController::class,'show']);
        Route::post('/update/{id}',[\App\Http\Controllers\ArticleController::class,'update']);
        Route::post('/delele/{id}',[\App\Http\Controllers\ArticleController::class,'delete']);

    });

Route::prefix('/article/like')
    ->middleware('auth:sanctum')
    ->group(function (){
        Route::post('/{id}',[\App\Http\Controllers\CommentController::class,'create']);
    });

Route::prefix('/comments/')
    ->middleware('auth:sanctum')
    ->group(function (){
        Route::post('/article/{id}',[\App\Http\Controllers\CommentController::class,'create']);
        Route::post('/update/{id}',[\App\Http\Controllers\CommentController::class,'update']);
    });



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
