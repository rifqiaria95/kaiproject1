<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Education;

class EducationController extends Controller
{
    public function index()
    {
        // Optimasi: Cache API response untuk education
        $education = \Cache::remember('api_education_data', 1800, function() {
            return Education::select(['id', 'institution', 'degree', 'field_of_study', 'start_date', 'end_date', 'user_id'])
                ->with(['user:id,name'])
                ->orderBy('start_date', 'desc')
                ->get();
        });

        return response()->json($education);
    }
}
