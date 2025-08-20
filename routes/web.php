<?php

use App\Http\Controllers\ClapController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/', [PostController::class, 'index'])->name('dashboard');
Route::get('/@{username}/{post:slug}', [PostController::class, 'show'])->name('post.show');
// Category filter
Route::get('/category/{category}', [PostController::class, 'category'])->name('post.byCategory');

Route::middleware(['auth', 'verified'])->group(function () {

    // Post management
    Route::prefix('post')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('post.create');
        Route::post('/create', [PostController::class, 'store'])->name('post.store');
        
        Route::get('/my-post', [PostController::class, 'myPosts'])->name('myPosts');
        
        Route::get('/{post:slug}', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/{post:slug}', [PostController::class, 'update'])->name('post.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('post.destroy');

        

    });

    // User profile
    Route::get('/@{user:username}', [PublicProfileController::class, 'show'])->name('profile.show');

    // Follow/Unfollow
    Route::post('/follow/{user}', [FollowerController::class, 'followUnfollow'])->name('follow');
    // Like/Clap
    Route::post('/clap/{post}', [ClapController::class, 'clap'])->name('clap');

    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');  
});



require __DIR__.'/auth.php';
