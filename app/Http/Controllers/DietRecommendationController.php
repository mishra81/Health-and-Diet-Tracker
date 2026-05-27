<?php

namespace App\Http\Controllers;

use App\Services\DietRecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DietRecommendationController extends Controller
{
    public function __invoke(Request $request, DietRecommendationService $service): View
    {
        return view('recommendations.index', [
            'profile' => $request->user()->profile,
            'goal' => $request->user()->goals()->where('status', 'active')->latest()->first(),
            'plans' => $service->forUser($request->user()),
        ]);
    }
}
