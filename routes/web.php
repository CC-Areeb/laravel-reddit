<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Reddit\RedditController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware(['guest', 'throttle:60,1'])->group(function () {
    Route::view('/register', 'authentication.index')->name('register');
    Route::post('/register', [AuthController::class, 'registerUser'])->name('register.users');
    Route::view('/login', 'authentication.login')->name('login');
    Route::post('/login', [AuthController::class, 'loginUsers'])->name('login.users');
});

// public and home routes
Route::get('/', [RedditController::class, 'home'])->name('home');
Route::view('/contact', 'reddit.contact')->name('contact');

// some issue with middleware and this get method, check controller code
Route::get('/subreddit/create', [RedditController::class, 'createSubreddit'])->name('subreddit.create');

// Auth and Reddit routes
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/subreddit/{id}', [RedditController::class, 'viewSubreddit'])->name('view.single.subreddit');
    Route::post('/post', [RedditController::class, 'redditPost'])->name('post.submit');
    Route::post('/subreddit/store', [RedditController::class, 'storeSubreddit'])->name('subreddit.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.users');
});


// Stop accessing post routes manually
Route::get('/logout', function () {
    return redirect()->route('home')->with('error', 'Invalid request');
});
