<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class OwnsModelPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function view(User $user, Model $model): bool
    {
        return $model->user_id === $user->id;
    }

    public function update(User $user, Model $model): bool
    {
        return $model->user_id === $user->id;
    }

    public function delete(User $user, Model $model): bool
    {
        return $model->user_id === $user->id;
    }
}
