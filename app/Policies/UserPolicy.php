<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRoleEnum;

class UserPolicy
{

    public function view(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->value || $user->role === UserRoleEnum::ADMIN->value;
    }

    public function cud(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->value;
    }

}
