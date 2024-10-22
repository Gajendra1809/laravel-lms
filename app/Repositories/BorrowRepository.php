<?php

namespace App\Repositories;

use App\Models\Borrow;

/**
 * Repository for the User model.
 *
 * @package App\Repositories
 *
 */
class BorrowRepository extends BaseRepository
{
    /**
     * Constructor to bind model to repo
     *
     * @param User $user User model
     */
    public function __construct(Borrow $borrow)
    {
        $this->model = $borrow;
    }

}
