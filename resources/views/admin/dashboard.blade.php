<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold text-white">Admin dashboard</h1></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 sm:grid-cols-4">
            <div class="fit-card"><p class="text-sm text-slate-300">Total users</p><p class="mt-2 text-3xl font-bold text-white">{{ $totalUsers }}</p></div>
            <div class="fit-card"><p class="text-sm text-slate-300">Active users</p><p class="mt-2 text-3xl font-bold text-white">{{ $activeUsers }}</p></div>
            <div class="fit-card"><p class="text-sm text-slate-300">Diet plans</p><p class="mt-2 text-3xl font-bold text-white">{{ $dietPlans }}</p></div>
            <div class="fit-card"><p class="text-sm text-slate-300">Avg calories</p><p class="mt-2 text-3xl font-bold text-white">{{ number_format($averageCalories) }}</p></div>
        </div>
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="fit-card">
                <h2 class="text-lg font-semibold text-white">Most used workout types</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($workoutTypes as $type)
                        <div><div class="mb-1 flex justify-between text-sm"><span class="capitalize">{{ $type->type }}</span><span>{{ $type->total }}</span></div><div class="h-2 rounded-full bg-slate-800"><div class="h-2 rounded-full bg-cyan-300" style="width: {{ min(100, $type->total * 12) }}%"></div></div></div>
                    @empty
                        <p class="text-slate-300">No workouts tracked yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="fit-card">
                <h2 class="text-lg font-semibold text-white">Management</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <a class="fit-button-secondary" href="{{ route('admin.users.index') }}">Users</a>
                    <a class="fit-button-secondary" href="{{ route('admin.diet-plans.index') }}">Diet plans</a>
                    <a class="fit-button-secondary" href="{{ route('admin.exercises.index') }}">Exercises</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
