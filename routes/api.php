<?php

use App\Http\Controllers\APIController\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Register
Route::post('/user/register', [AuthenticationController::class, 'store']);

// Authentication 
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/change/password', [AuthenticationController::class, 'change_password'])->middleware('auth:sanctum');
Route::post('/password/reset', [AuthenticationController::class, 'forgot_password']);