<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HealthController;
use App\Http\Controllers\ClientController;

Route::get('health', [HealthController::class, 'index']);
Route::apiResource('clients', ClientController::class);
