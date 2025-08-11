<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    public function index()
    {
        // Optimasi: Cache API response untuk about
        $about = \Cache::remember('api_about_data', 1800, function() {
            return About::select(['id', 'title', 'subtitle', 'description', 'image', 'video', 'address', 'phone', 'email', 'facebook', 'instagram', 'twitter', 'tiktok', 'youtube', 'created_by', 'created_at'])
                ->with(['creator:id,name'])
                ->get()
                ->map(function($item) {
                    // Tambahkan URL gambar yang dinamis dengan validasi file
                    if ($item->image) {
                        $imagePath = public_path('images/' . $item->image);
                        if (File::exists($imagePath)) {
                            // Gunakan URL yang dinamis berdasarkan environment
                            $baseUrl = config('app.env') === 'production' 
                                ? config('app.url') 
                                : url('/');
                            $item->image_url = $baseUrl . '/api/images/' . $item->image;
                        } else {
                            // Jika file tidak ada, set image_url ke null
                            $item->image_url = null;
                            // Log warning untuk debugging
                            \Log::warning("Image file not found: {$item->image}");
                        }
                    } else {
                        $item->image_url = null;
                    }
                    return $item;
                });
        });

        return response()->json($about);
    }
}
