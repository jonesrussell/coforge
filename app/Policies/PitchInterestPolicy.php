<?php

namespace App\Policies;

use App\Models\Pitch;
use App\Models\PitchInterest;
use App\Models\User;

class PitchInterestPolicy
{
    /**
     * Determine whether the user can view any interests on a pitch (as pitch owner).
     */
    public function viewAny(User $user, Pitch $pitch): bool
    {
        return $pitch->user_id === $user->id;
    }

    /**
     * Determine whether the user can create an interest (express interest in a pitch).
     */
    public function create(User $user, Pitch $pitch): bool
    {
        if ($pitch->user_id === $user->id) {
            return false;
        }

        return $pitch->status === 'published';
    }

    /**
     * Determine whether the user can update the interest (accept/decline as pitch owner).
     */
    public function update(User $user, PitchInterest $interest): bool
    {
        return $interest->pitch->user_id === $user->id;
    }
}
