<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ExperienceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Chat conversation endpoint
Route::post('/chat', [ChatController::class, 'handle']);

// About endpoint
Route::get('/about', [AboutController::class, 'index']);

// Education endpoint
Route::get('/education', [EducationController::class, 'index']);

// News endpoint
Route::get('/news', [NewsController::class, 'index']);

// Experience endpoint
Route::get('/experience', [ExperienceController::class, 'index']);
