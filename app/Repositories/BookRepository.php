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

    /**
     * Retrieves a book by its UUID and locks it for read/update.
     *
     * @param string $uuid The UUID of the book to lock and retrieve.
     * @return \App\Models\Book|null The locked book if found, null otherwise.
     */
    public function getBookandLock(String $uuid){
        return $this->model->where('uuid', $uuid)->lockForUpdate()->first();
    }

    /**
     * Retrieves the top $limit books that have been borrowed the most.
     *
     * @param int $limit The number of top borrowed books to retrieve.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mostBorrowedBooks($limit){
        return $this->model::select('id', 'title', 'isbn')
        ->withCount('borrows')
        ->orderByDesc('borrows_count')
        ->limit($limit)
        ->get();
    }

}
