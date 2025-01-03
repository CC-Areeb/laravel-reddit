<?php

use App\Http\Controllers\APIController\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Register
Route::post('/user/register', [AuthenticationController::class, 'store']);

// Authentication 
Route::post('/login', [AuthenticationController::class, 'login'])->middleware('guest');