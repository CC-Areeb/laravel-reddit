<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Reddit\RedditController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerUser'])->name('register.users');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginUsers'])->name('login.users');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.users');

// Reddit routes
Route::get('/', [RedditController::class, 'home'])->name('home');
Route::get('/contact', [RedditController::class, 'contact'])->name('contact');
Route::get('/subreddit/{id}', [RedditController::class, 'viewSubreddit'])->name('view.single.subreddit');
Route::post('/post', [RedditController::class, 'redditPost'])->name('post.submit');
