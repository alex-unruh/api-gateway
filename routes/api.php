<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogRequestController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Http;

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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/islive', fn () => response(['message' => "API's are live"]));

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    Route::controller(UsersController::class)->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', 'index');
            Route::post('/store', 'store');
        });
    });
});

Route::any('/gate/{endpoint}', LogRequestController::class)->where('endpoint', '.*');
