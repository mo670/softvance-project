<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\PostController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/contact-message', [ContactMessageController::class, 'store']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', fn () => User::with('roles')->get());
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store'])->middleware('permission:post.create,api');
    Route::put('/posts/{post}', [PostController::class, 'update'])->middleware('permission:post.create,api');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware('permission:post.delete,api');

    Route::get('/admin', function () {
        return 'Admin Only';
    })->middleware('role:admin,api');

    Route::get('/create-post', function () {
        return 'Post Create Allowed';
    })->middleware('permission:post.create,api');

    Route::get('/delete-post', function () {
        return 'Post Delete Allowed';
    })->middleware('permission:post.delete,api');
});
