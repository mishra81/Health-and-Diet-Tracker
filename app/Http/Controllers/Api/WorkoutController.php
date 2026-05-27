<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkoutRequest;
use App\Models\Workout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->workouts()->latest('logged_at')->paginate(20));
    }

    public function store(WorkoutRequest $request): JsonResponse
    {
        return response()->json($request->user()->workouts()->create($request->validated()), 201);
    }

    public function show(Workout $workout): JsonResponse
    {
        abort_unless($workout->user_id === auth()->id(), 403);

        return response()->json($workout);
    }

    public function update(WorkoutRequest $request, Workout $workout): JsonResponse
    {
        abort_unless($workout->user_id === auth()->id(), 403);
        $workout->update($request->validated());

        return response()->json($workout);
    }

    public function destroy(Workout $workout): JsonResponse
    {
        abort_unless($workout->user_id === auth()->id(), 403);
        $workout->delete();

        return response()->json(status: 204);
    }
}
