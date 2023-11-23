<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
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

Route::post('/auth/login',  'AuthController@login');

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::get('/profile', 'profile');
        Route::get('/logout', 'logout');
    });

    Route::prefix('post')->controller(PostController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', "store");
        Route::post('/{id}', "edit");
        Route::delete('/{id}/delete', 'destroy');
    });

    Route::prefix('comment')->controller(CommentController::class)->group(function () {
        Route::post('/', "store");
        Route::delete('/{id}/delete', 'destroy');
    });
});
