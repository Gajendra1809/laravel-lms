<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRoleEnum;

class BorrowPolicy
{
    
    public function view(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->label() || $user->role === UserRoleEnum::ADMIN->label();
    }
    
}
