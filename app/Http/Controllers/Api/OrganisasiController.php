<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organisasi;

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
                    ->get();
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
