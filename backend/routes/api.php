<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HealthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Contracts\ContractController;
use App\Http\Controllers\Contracts\ElectricityContractController;

Route::get('health', [HealthController::class, 'index']);
Route::apiResource('clients', ClientController::class);
Route::apiResource('contracts', ContractController::class);
Route::apiResource('electricity-contracts', ElectricityContractController::class);
