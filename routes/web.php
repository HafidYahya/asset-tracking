<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\MasterAssetController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware('auth');
Route::get('login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [AuthController::class, 'store'])->name('login.store')->middleware('guest');
Route::post('logout', [AuthController::class, 'destroy'])->name('logout')->middleware('auth');
Route::resource('users', controller: UserController::class)->middleware('auth');
Route::resource('master-assets', controller: MasterAssetController::class)->middleware('auth');
Route::resource('assets', controller: AssetController::class)->middleware('auth');
Route::get('tracking', [TrackingController::class, 'index'])->name('tracking.index')->middleware('auth');
Route::patch('master-assets/{master_asset}/change-status', [MasterAssetController::class, 'changeStatus'])
    ->name('master-assets.change-status')
    ->middleware('auth');
