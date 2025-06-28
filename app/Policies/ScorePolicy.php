<?php

namespace App\Policies;

use App\Models\Score;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScorePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManajemen() || $user->isDosen();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Score $score): bool
    {
        return $user->isAdmin() || $user->isManajemen() || 
               ($user->isDosen() && $user->id === $score->teacher_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isDosen();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Score $score): bool
    {
        return $user->isDosen() && $user->id === $score->teacher_id && !$score->validated;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Score $score): bool
    {
        return ($user->isDosen() && $user->id === $score->teacher_id && !$score->validated) ||
               $user->isAdmin();
    }

    /**
     * Determine whether the user can validate scores.
     */
    public function validate(User $user, Score $score = null): bool
    {
        return $user->isAdmin() || $user->isManajemen();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Score $score): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Score $score): bool
    {
        return false;
    }
}
