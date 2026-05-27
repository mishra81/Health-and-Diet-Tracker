<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DietPlanController as AdminDietPlanController;
use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DietRecommendationController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaterIntakeController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('meals', MealController::class)->except('show');
    Route::resource('workouts', WorkoutController::class)->except('show');
    Route::resource('goals', GoalController::class)->except('show');
    Route::get('water', [WaterIntakeController::class, 'index'])->name('water-intakes.index');
    Route::post('water', [WaterIntakeController::class, 'store'])->name('water-intakes.store');
    Route::delete('water/{waterIntake}', [WaterIntakeController::class, 'destroy'])->name('water-intakes.destroy');
    Route::get('recommendations', DietRecommendationController::class)->name('recommendations.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'can:access-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::resource('users', AdminUserController::class)->except(['show', 'create', 'store']);
        Route::resource('diet-plans', AdminDietPlanController::class)->except('show');
        Route::resource('exercises', AdminExerciseController::class)->except('show');
    });

require __DIR__.'/auth.php';
