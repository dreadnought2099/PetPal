<?php

namespace App\Policies;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdoptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can approve the adoption request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Adoption  $adoption
     * @return bool
     */
    public function approve(User $user, Adoption $adoption): bool
    {
        // Only allow admins to approve adoption requests
        return $user->hasRole('Administrator|Shelter');
    }

    /**
     * Determine whether the user can reject the adoption request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Adoption  $adoption
     * @return bool
     */
    public function reject(User $user, Adoption $adoption): bool
    {
        // Only allow admins to reject adoption requests
        return $user->hasRole('Administrator|Shelter');
    }

    /**
     * Determine whether the user can delete the adoption request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Adoption  $adoption
     * @return bool
     */
    public function delete(User $user, Adoption $adoption): bool
    {
        // Only allow the user who created the adoption request to delete it if it's pending
        return $user->id === $adoption->user_id && $adoption->status === 'pending';
    }

    // You can also define other policy methods if necessary, like view, create, etc.
}
