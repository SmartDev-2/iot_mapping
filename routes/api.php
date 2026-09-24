<?php

use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::post('/location', [LocationController::class, 'store']);
Route::get('/location/latest', [LocationController::class, 'latest']);
