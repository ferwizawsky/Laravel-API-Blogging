<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\UserController;
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

Route::post('/auth/register',  'AuthController@register');
Route::post('/auth/login',  'AuthController@login');



Route::middleware("auth:sanctum")->group(function () {

    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        // Route::post('/', "store");
        // Route::get('/{id}', "get");
        // Route::post('/{id}', "edit");
        // Route::delete('/{id}/delete', 'destroy');
    });
});
