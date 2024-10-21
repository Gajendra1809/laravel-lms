<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRoleEnum;

/**
 *
 * Policy for the User model.
 *
 * @package App\Policies
 *
 */
class UserPolicy
{

    /**
     * Determine if the user can view the resource.
     *
     * @param User $user The user to check permissions for.
     * @return bool True if the user can view the resource, false otherwise.
     */
    public function view(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->label() || $user->role === UserRoleEnum::ADMIN->label();
    }

    /**
     * Determine if the user can create, update, or delete the resource.
     *
     * @param User $user The user to check permissions for.
     * @return bool True if the user can create, update, or delete the resource, false otherwise.
     */
    public function cud(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->label();
    }

}
