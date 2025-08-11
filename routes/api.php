<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\AuthController;

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

// Program endpoint
Route::get('/programs/open', [App\Http\Controllers\Api\ProgramController::class, 'getOpenPrograms']);

// Auth endpoints
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Route untuk gambar dinamis
Route::get('/images/{filename}', function ($filename) {
    $path = public_path('images/' . $filename);
    
    // Debug: log path yang dicari
    \Log::info("Image request: {$filename}, Path: {$path}");
    
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
    
    \Log::info("Image served: {$filename}, Type: {$type}, Size: " . strlen($file));
    
    return response($file, 200)
        ->header('Content-Type', $type)
        ->header('Cache-Control', 'public, max-age=31536000')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type');
})->where('filename', '.*');

// Route testing untuk debug about image URL
Route::get('/debug/about-image', function () {
    $about = \App\Models\About::first();
    
    if (!$about) {
        return response()->json(['error' => 'No about data found']);
    }
    
    $result = [
        'id' => $about->id,
        'title' => $about->title,
        'image_path' => $about->image,
        'image_type' => strpos($about->image, 'uploads/') === 0 ? 'storage' : 'public',
    ];
    
    if ($about->image) {
        if (strpos($about->image, 'uploads/') === 0) {
            $result['exists_in_storage'] = \Illuminate\Support\Facades\Storage::disk('public')->exists($about->image);
            $result['storage_url'] = \Illuminate\Support\Facades\Storage::disk('public')->url($about->image);
        } else {
            $imagePath = public_path('images/' . $about->image);
            $result['exists_in_public'] = \Illuminate\Support\Facades\File::exists($imagePath);
            $baseUrl = config('app.env') === 'production' 
                ? rtrim(config('app.url'), '/') 
                : rtrim(url('/'), '/');
            $result['public_url'] = $baseUrl . '/images/' . $about->image;
        }
    }
    
    return response()->json($result);
});
