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
            return Experience::select(['id', 'company', 'position', 'description', 'start_date', 'end_date', 'user_id'])
                ->with(['user:id,name'])
                ->orderBy('start_date', 'desc')
                ->get();
        });

        return response()->json($experience);
    }
}
