<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\CommentController;

Route::middleware(['auth:api', 'admin'])->group(function () {
    // News Routes
    Route::get('/news', [NewsController::class, 'index']);
    Route::post('/news', [NewsController::class, 'store']);
    Route::get('/news/{id}', [NewsController::class, 'show']);
    Route::put('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);
    
    // Comments Routes
    Route::post('/news/{newsId}/comments', [CommentController::class, 'store']);
});

Route::post('/register', function (Request $request) {
    // User registration logic here
});

Route::post('/login', function (Request $request) {
    // User authentication logic here
});
