<?php

namespace App\Repositories;

use App\Models\Borrow;

/**
 * Repository for the Borrow model.
 *
 * @package App\Repositories
 *
 */
class BorrowRepository extends BaseRepository
{
    /**
     * Constructor to bind model to repo
     *
     * @param Borrow $borrow Borrow model
     */
    public function __construct(Borrow $borrow)
    {
        $this->model = $borrow;
    }

}
