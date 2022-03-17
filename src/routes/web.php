<?php

use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkStampController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // 勤務日のルーティング
    Route::resource('/works', WorkController::class)
        ->only(["index", "store", "update", "destroy"]);
    Route::get('/home', [WorkController::class, 'index'])->name('home');

    // 打刻時刻のルーティング
    Route::resource('/work_stamps', WorkStampController::class)
        ->only(["store", "update", "destroy"]);
});
