<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;
use App\Enums\UserRoleEnum;

class BookPolicy
{
    
    /**
     * Determine if the user can create a new resource.
     *
     * @param User $user The user to check permissions for.
     * @return bool True if the user can create a new resource, false otherwise.
     */
    public function create(User $user){
        return $user->role === UserRoleEnum::SUPERADMIN->label() || $user->role === UserRoleEnum::ADMIN->label();
    }

    /**
     * Determine if the user can update and/or delete the given book.
     *
     * @param User $user The user to check permissions for.
     * @param Book $book The book to check permissions for.
     *
     * @return bool True if the user can update and/or delete the book, false otherwise.
     */
    public function updateDel(User $user, Book $book){
        return $user->role === UserRoleEnum::SUPERADMIN->label() || ($user->role === UserRoleEnum::ADMIN->label() && $book->admin_id === $user->id);
    }
}
