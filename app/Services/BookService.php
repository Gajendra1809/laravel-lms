<?php

namespace App\Services;

use App\Exports\BooksExport;
use App\Imports\BooksImport;
use App\Repositories\BookRepository;
use Illuminate\Support\Facades\Gate;

class BookService
{
    /**
     * Constructs a new instance of the BookService, injecting
     * the required BookRepository via the constructor.
     *
     * @param BookRepository $bookRepository The book repository to be used.
     */
    public function __construct(
        protected BookRepository $bookRepository
    ){
    }

    /**
     * Retrieves all books from the database.
     *
     * @return \Illuminate\Support\Collection A collection of all books.
     */
    public function all(){
        return $this->bookRepository->all();
    }

    /**
     * Creates a new book in the database.
     *
     * @param array $data The data to be used for creating the book.
     * The admin_id will be automatically set to the current user.
     *
     * @return \App\Models\Book The created book.
     */
    public function create(array $data){
        $data['admin_id'] = auth()->id();
        return $this->bookRepository->create($data);
    }

    /**
     * Retrieves a book by its ID.
     *
     * @param int $id The ID of the book to retrieve.
     *
     * @return \App\Models\Book|bool The book if found, false otherwise.
     */
    public function getBookById(int $id){
        $book = $this->bookRepository->find($id);
        if($book==null) {
            return false;
        }
        return $book;
    }


    /**
     * Retrieves a book by its UUID.
     *
     * @param string $uuid The UUID of the book to retrieve.
     *
     * @return \App\Models\Book|bool The book if found, false otherwise.
     */
    public function getBook(string $uuid){
        return $this->bookRepository->findByUuid($uuid);
    }

    /**
     * Updates a book by its UUID with the given data.
     *
     * @param string $uuid The UUID of the book to update.
     * @param array $data The data to be used for updating the book.
     *
     * @return \App\Models\Book|bool The updated book if found, false otherwise.
     */
    public function updateByUuid(string $uuid, array $data){
        $book = $this->getBook($uuid);
        if(!$book) {
            return false;
        }
        $this->authorize($book);
        return $this->bookRepository->update($uuid, $data);
    }

    /**
     * Deletes a book by its UUID.
     *
     * @param string $uuid The UUID of the book to delete.
     *
     * @return bool True if the book is successfully deleted, false otherwise.
     */
    public function deleteByUuid(string $uuid){
        $book = $this->getBook($uuid);
        if(!$book) {
            return false;
        }
        $this->authorize($book);
        return $this->bookRepository->delete($uuid);
    }

    /**
     * Searches for books based on a query data.
     *
     * @param mixed $query The search query data.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of books
     * that match the query.
     */
    public function search($query){
        return $this->bookRepository->search($query);
    }

    /**
     * Checks if the current user has the permission to update or delete the given book.
     *
     * @param \App\Models\Book $book The book to check permissions for.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException if the user does not have the required permission.
     *
     * @return void
     */
    public function authorize($book){
        Gate::authorize('updateDel', $book);
    }

    /**
     * Exports all books in the database to an Excel file and returns the
     * response.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse The response
     * containing the Excel file.
     */
    public function exportAllBooks(){
        return $this->bookRepository->exportAll(new BooksExport, 'books.xlsx');
    }

    /**
     * Imports books from an Excel file into the database.
     *
     * @return \Illuminate\Support\Collection The collection of imported books.
     */
    public function importBooks(){
        return $this->bookRepository->importData(new BooksImport, request()->file('file'));
    }
}
