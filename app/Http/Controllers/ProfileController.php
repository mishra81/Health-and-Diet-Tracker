<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\HealthMetricService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'profile' => $request->user()->profile()->firstOrNew(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, HealthMetricService $healthMetricService): RedirectResponse
    {
        $validated = $request->validated();
        $request->user()->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $profileData = collect($validated)->only([
            'age',
            'gender',
            'height_cm',
            'weight_kg',
            'goal_weight_kg',
            'activity_level',
            'dietary_preference',
        ])->toArray();

        $profileData['activity_level'] ??= $request->user()->profile?->activity_level ?? 'moderate';
        $profileData['dietary_preference'] ??= $request->user()->profile?->dietary_preference ?? 'balanced';

        if ($request->hasFile('profile_photo')) {
            $oldPhoto = $request->user()->profile?->profile_photo_path;
            $profileData['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        $profile = $request->user()->profile()->updateOrCreate(['user_id' => $request->user()->id], $profileData);

        $request->user()->progressLogs()->updateOrCreate(
            ['logged_at' => now()->toDateString()],
            [
                'weight_kg' => $profile->weight_kg,
                'bmi' => $healthMetricService->bmi($profile->weight_kg, $profile->height_cm),
                'calories_consumed' => $request->user()->meals()->whereDate('logged_at', now()->toDateString())->sum('calories'),
                'calories_burned' => $request->user()->workouts()->whereDate('logged_at', now()->toDateString())->sum('calories_burned'),
            ]
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
