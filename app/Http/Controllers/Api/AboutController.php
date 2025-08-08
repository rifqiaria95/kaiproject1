<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        // Optimasi: Cache API response untuk about
        $about = \Cache::remember('api_about_data', 1800, function() {
            return About::select(['id', 'title', 'subtitle', 'description', 'created_by', 'created_at'])
                ->with(['creator:id,name'])
                ->get();
        });

        return response()->json($about);
    }
}
