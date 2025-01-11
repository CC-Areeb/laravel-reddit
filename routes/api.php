<?php

use App\Http\Controllers\APIController\AuthenticationController;
use App\Http\Controllers\APIController\CommunityController;
use App\Http\Controllers\APIController\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/user/register', [AuthenticationController::class, 'store']);

// Authentication
Route::post('/user/register', [AuthenticationController::class, 'store']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/change/password', [AuthenticationController::class, 'change_password'])->middleware('auth:sanctum');
Route::post('/password/reset', [AuthenticationController::class, 'forgot_password']);

Route::middleware('auth:sanctum')->group(function () {
    // Super Admin
    Route::get('/users', [UserController::class, 'showUsers']);


    // Reddit communities
    Route::get('/sub_reddit', [CommunityController::class, 'showCommunities']);
    Route::post('/sub_reddit/create', [CommunityController::class, 'storeCommunity']);
    Route::post('/update/moderator', [CommunityController::class, 'storeCommunity']);
    Route::post('/accept/users', [CommunityController::class, 'addUsersToCommunities']);
});