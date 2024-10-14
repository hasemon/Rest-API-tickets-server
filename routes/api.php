<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// Logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
