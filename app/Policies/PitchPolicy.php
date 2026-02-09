<?php

namespace App\Policies;

use App\Models\Pitch;
use App\Models\User;

class PitchPolicy
{
    /**
     * Determine whether the user can view the pitch.
     * Published: anyone. Draft: owner only.
     */
    public function view(?User $user, Pitch $pitch): bool
    {
        if ($pitch->status === 'published') {
            return true;
        }

        return $user !== null && $pitch->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the pitch.
     */
    public function update(User $user, Pitch $pitch): bool
    {
        return $pitch->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the pitch.
     */
    public function delete(User $user, Pitch $pitch): bool
    {
        return $pitch->user_id === $user->id;
    }

    /**
     * Determine whether the user can create pitches.
     */
    public function create(User $user): bool
    {
        return true;
    }
}
