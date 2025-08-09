<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware('api')->group(function () {
    Route::get('/customers/search', [CustomerController::class, 'search'])
        ->middleware('auth:sanctum')
        ->name('api.customers.search');
});