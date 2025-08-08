<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;

class ExperienceController extends Controller
{
    public function index()
    {
        // Optimasi: Cache API response untuk experience
        $experience = \Cache::remember('api_experience_data', 1800, function() {
            return Experience::select(['id', 'title', 'subtitle', 'company', 'year', 'description', 'created_by', 'created_at'])
                ->with(['createdBy:id,name'])
                ->orderBy('year', 'desc')
                ->get();
        });

        return response()->json($experience);
    }
}
