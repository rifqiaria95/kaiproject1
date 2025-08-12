<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GaleriController extends Controller
{
    public function index()
    {
        // Optimasi: Cache API response untuk galeri
        $galeri = \Cache::remember('api_galeri_data', 1800, function() {
            return Galeri::select(['id', 'title', 'subtitle', 'description', 'image','kategori_galeri_id', 'created_by', 'created_at'])
                ->with(['createdBy:id,name', 'kategoriGaleri:id,name'])
                ->get()
                ->map(function($item) {
                    // Tambahkan URL gambar yang dinamis dengan validasi file
                    if ($item->image) {
                        // Cek apakah ini path storage (mengandung 'uploads/')
                        if (strpos($item->image, 'uploads/') === 0) {
                            // Gunakan Storage URL untuk gambar yang disimpan via FileStorageService
                            if (Storage::disk('public')->exists($item->image)) {
                                $item->image_url = Storage::disk('public')->url($item->image);
                            } else {
                                $item->image_url = null;
                                \Log::warning("Image file not found in storage: {$item->image}");
                            }
                        } else {
                            // Ini adalah file lama yang disimpan di public/images/
                            $imagePath = public_path('images/' . $item->image);
                            if (File::exists($imagePath)) {
                                // Gunakan URL yang dinamis berdasarkan environment dan hindari double slash
                                $baseUrl = config('app.env') === 'production'
                                    ? rtrim(config('app.url'), '/')
                                    : rtrim(url('/'), '/');
                                $item->image_url = $baseUrl . '/images/' . $item->image;
                            } else {
                                $item->image_url = null;
                                \Log::warning("Image file not found in public/images: {$item->image}");
                            }
                        }
                    } else {
                        $item->image_url = null;
                    }
                    return $item;
                });
        });

        return response()->json($galeri);
    }
}
