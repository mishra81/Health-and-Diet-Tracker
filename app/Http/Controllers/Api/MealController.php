<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealRequest;
use App\Models\Meal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->meals()->latest('logged_at')->paginate(20));
    }

    public function store(MealRequest $request): JsonResponse
    {
        return response()->json($request->user()->meals()->create($request->validated()), 201);
    }

    public function show(Meal $meal): JsonResponse
    {
        abort_unless($meal->user_id === auth()->id(), 403);

        return response()->json($meal);
    }

    public function update(MealRequest $request, Meal $meal): JsonResponse
    {
        abort_unless($meal->user_id === auth()->id(), 403);
        $meal->update($request->validated());

        return response()->json($meal);
    }

    public function destroy(Meal $meal): JsonResponse
    {
        abort_unless($meal->user_id === auth()->id(), 403);
        $meal->delete();

        return response()->json(status: 204);
    }
}
