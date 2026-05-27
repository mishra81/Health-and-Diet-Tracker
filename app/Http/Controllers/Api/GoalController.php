<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoalRequest;
use App\Models\Goal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($request->user()->goals()->latest()->paginate(20));
    }

    public function store(GoalRequest $request): JsonResponse
    {
        return response()->json($request->user()->goals()->create($request->validated()), 201);
    }

    public function show(Goal $goal): JsonResponse
    {
        abort_unless($goal->user_id === auth()->id(), 403);

        return response()->json($goal);
    }

    public function update(GoalRequest $request, Goal $goal): JsonResponse
    {
        abort_unless($goal->user_id === auth()->id(), 403);
        $goal->update($request->validated());

        return response()->json($goal);
    }

    public function destroy(Goal $goal): JsonResponse
    {
        abort_unless($goal->user_id === auth()->id(), 403);
        $goal->delete();

        return response()->json(status: 204);
    }
}
