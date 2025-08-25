<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organisasi;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OrganisasiController extends Controller
{
    public function index()
    {
        try {
            // Optimasi: Cache API response untuk organisasi
            $organisasi = \Cache::remember('api_organisasi_data', 1800, function() {
                return Organisasi::select(['id', 'nama', 'jabatan', 'tahun', 'lokasi', 'deskripsi', 'image', 'created_by', 'created_at', 'updated_at'])
                    ->with(['createdBy:id,name'])
                    ->orderBy('tahun', 'desc')
                    ->get()
                    ->map(function($item) {
                        // Tambahkan URL gambar yang dinamis dengan validasi file
                        if ($item->image) {
                            // Cek apakah ini path storage (mengandung 'uploads/' atau 'organisasi/')
                            if (strpos($item->image, 'uploads/') === 0 || strpos($item->image, 'organisasi/') === 0) {
                                // Generate Storage URL - di production mungkin file ada tapi symlink belum dibuat
                                $baseUrl = config('app.env') === 'production'
                                    ? rtrim(config('app.url'), '/')
                                    : rtrim(url('/'), '/');

                                // Coba Storage URL terlebih dahulu
                                if (Storage::disk('public')->exists($item->image)) {
                                    $item->image_url = Storage::disk('public')->url($item->image);
                                } else {
                                    // Fallback ke URL manual jika storage symlink belum ada
                                    $item->image_url = $baseUrl . '/storage/' . $item->image;
                                    \Log::info("Using manual storage URL for: {$item->image}");
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

            return response()->json($organisasi);
        } catch (\Exception $e) {
            \Log::error('Error fetching organisasi data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data organisasi',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
