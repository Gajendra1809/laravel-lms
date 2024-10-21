<?php

namespace App\Repositories;

use App\Models\Book;

/**
 * Repository for the User model.
 *
 * @package App\Repositories
 *
 */
class BookRepository extends BaseRepository
{
    /**
     * Constructor to bind model to repo
     *
     * @param Book $book Book model
     */
    public function __construct(Book $book)
    {
        $this->model = $book;
    }

}
