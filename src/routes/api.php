<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;


Route::prefix('/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('{user}', [UserController::class, 'update']);
    Route::post('{user}/deposit', [TransactionController::class, 'deposit']);
    Route::post('{user}/transfer', [TransactionController::class, 'transfer']);
});
