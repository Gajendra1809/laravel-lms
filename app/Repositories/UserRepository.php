<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Repository for the User model.
 *
 * @package App\Repositories
 *
 */
class UserRepository extends BaseRepository
{
    /**
     * Constructor to bind model to repo
     *
     * @param User $user User model
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Search for users based on the provided query data.
     *
     * @param string $query
     *
     * @return \Illuminate\Support\Collection
     */
    public function search($query){
        return User::search($query);
    }

}
