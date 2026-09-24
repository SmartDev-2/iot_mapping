<?php
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;
Route::get('/devices', [DeviceController::class, 'index']);
Route::post('/location', [LocationController::class, 'store']);
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/device/{device}/latest', [LocationController::class, 'latest']);
Route::get('/device/{device}/history', [LocationController::class, 'history']);
