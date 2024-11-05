<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {

        if ($model->roles->pluck('name')->contains('Admin')) return false; // yla kan user machi admin ( admin bgha yms7 admin khor  )

        return $user->roles->pluck('name')->contains('Admin') || $model->id === $user->id; // yla kan user hwa mol account  aw admin
    }


    /**
     * Determine whether the user can delete account the model.
     */

    public function delete_account(User $authUser, User $user): bool
    {

        return $authUser->id === $user->id && !$user->roles->pluck('name')->contains('Admin'); // yla kan user 3adi ymkn yder delete l account dyalo wlki admin la mimknch
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
