<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\StudentController;
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

Route::get('/post/{id}',  'PostController@get');
Route::get('/post',  'PostController@index');
Route::post('/auth/register',  'AuthController@register');
Route::post('/auth/login',  'AuthController@login');

Route::middleware("auth:sanctum")->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::get('/profile', 'profile');
        Route::get('/logout', 'logout');
    });


    Route::prefix('student')->controller(StudentController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/jadwal', 'jadwal');
    });

    Route::prefix('kelas')->controller(KelasController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', "store");
        Route::get('/{id}', "get");
        Route::post('/{id}/update', "edit");
        Route::delete('/{id}/delete', 'destroy');
    });


    Route::prefix('jadwal')
        // ->middleware("role:1")
        ->controller(JadwalController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', "store");
            Route::get('/{id}', "get");
            Route::post('/{id}/update', "edit");
            Route::delete('/{id}/delete', 'destroy');
        });

    Route::prefix('user')->middleware("role:2")->controller(UserController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', "store");
        Route::get('/{id}', "get");
        Route::post('/{id}/update', "edit");
        Route::delete('/{id}/delete', 'destroy');
    });

    Route::prefix('absen')->middleware("role:2")->controller(AbsensiController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/detail', 'indexDetail');

        // Route::get('/{id}/detail', "get");
        Route::post('/', "store");
        // Route::post('/{id}/update', "edit");
        Route::delete('/{id}/delete', 'destroy');
    });
});
