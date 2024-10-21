<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;
use App\Enums\UserRoleEnum;

class BookPolicy
{
    /**
     * Determine if the user can create, update, or delete the resource.
     *
     * @param User $user The user to check permissions for.
     * @return bool True if the user can create, update, or delete the resource, false otherwise.
     */
    public function create(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->label() || $user->role === UserRoleEnum::ADMIN->label();
    }

    public function updateDel(User $user, Book $book){
        return $user->role === UserRoleEnum::SUPERADMIN->label() || ($user->role === UserRoleEnum::ADMIN->label() && $book->admin_id === $user->id);
    }
}
