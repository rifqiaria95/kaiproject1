<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GaleriController;
use App\Http\Controllers\Api\OrganisasiController;
use App\Http\Controllers\Api\TestimoniController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Chat conversation endpoint
Route::post('/chat', [ChatController::class, 'handle']);

// About endpoint
Route::get('/about', [AboutController::class, 'index']);

// Galeri endpoint
Route::get('/galeri', [GaleriController::class, 'index']);

// Kategori Galeri endpoint
Route::get('/kategori-galeri', [App\Http\Controllers\Api\KategoriGaleriController::class, 'index']);

// Education endpoint
Route::get('/education', [EducationController::class, 'index']);

// Organisasi endpoint
Route::get('/organisasi', [OrganisasiController::class, 'index']);

// News endpoint
Route::get('/news', [NewsController::class, 'index']);

// Experience endpoint
Route::get('/experience', [ExperienceController::class, 'index']);

// Program endpoint
Route::get('/programs/open', [App\Http\Controllers\Api\ProgramController::class, 'getOpenPrograms']);

// Testimoni endpoint
Route::get('/testimoni', [TestimoniController::class, 'index']);

// Auth endpoints
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Route untuk gambar dinamis
Route::get('/images/{filename}', function ($filename) {
    $path = public_path('images/' . $filename);

    if (!file_exists($path)) {
        \Log::warning("Image file not found: {$path}");
        // Return default image instead of 404
        $defaultPath = public_path('images/about/about-img.jpg');
        if (file_exists($defaultPath)) {
            $file = file_get_contents($defaultPath);
            $type = mime_content_type($defaultPath);
            return response($file, 200)
                ->header('Content-Type', $type)
                ->header('Cache-Control', 'public, max-age=31536000');
        }
        abort(404, "Image not found: {$filename}");
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type');
})->where('filename', '.*');
