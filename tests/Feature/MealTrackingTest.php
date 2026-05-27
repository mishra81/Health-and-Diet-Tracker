<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_meal_updates_dashboard_calories(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/meals', [
                'name' => 'Paneer bowl',
                'calories' => 550,
                'protein_g' => 32,
                'carbs_g' => 48,
                'fat_g' => 18,
                'meal_type' => 'lunch',
                'logged_at' => now()->toDateString(),
            ]);

        $response->assertRedirect('/meals');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('550');
    }

    public function test_meals_index_uses_today_when_date_filter_is_missing(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/meals')
            ->assertOk()
            ->assertSee(now()->toDateString());
    }
}
