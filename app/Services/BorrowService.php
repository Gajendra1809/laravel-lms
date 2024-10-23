<?php

namespace App\Services;

use App\Repositories\BorrowRepository;
use App\Enums\StatusEnum;

class BorrowService
{
    /**
     * Constructs a new instance of the BorrowService, injecting
     * the required BorrowRepository and BookService via the constructor.
     *
     * @param BorrowRepository $borrowRepository The borrow repository to be used.
     * @param BookService $bookService The book service to be used.
     */
    public function __construct(
        protected BorrowRepository $borrowRepository,
        protected BookService $bookService
    ){
    }

    /**
     * Retrieves all current book borrowings.
     *
     * @return \Illuminate\Support\Collection The collection of current borrow records.
     */
    public function allCurrentBorrows(){
        $conditions = ['return_date' => null];
        $relations = ['user', 'book'];
        return $this->borrowRepository->findWithConditions($conditions, $relations);
    }

    /**
     * Borrows a book, given the uuid of the book.
     *
     * @param string $uuid The uuid of the book to borrow.
     *
     * @return array The response array
     */
    public function borrow(string $uuid){
        $book = $this->bookService->getBook($uuid);
        if(!$book) {
            $response['success'] = false;
            $response['msg'] = 'Book not found';
            $response['status'] = 404;
            $response['data'] = null;
        }elseif($book->available == StatusEnum::NOTAVAILABLE->label()){
            $response['success'] = false;
            $response['msg'] = 'Book not available to borrow, wait for it to be available';
            $response['status'] = 400;
            $response['data'] = null;
        }else{
            $borrowData = [
                'book_id' => $book->id,
                'user_id' => auth()->user()->id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(14),
            ];
            $borrow = $this->borrowRepository->create($borrowData);
            $book->update(['available' => StatusEnum::NOTAVAILABLE->value]);
            $response['success'] = true;
            $response['msg'] = 'Book borrowed successfully';
            $response['status'] = 200;
            $response['data'] = $borrow;
        }
        return $response;
    }

    /**
     * Retrieves the books borrowed by the user.
     *
     * @return array The response array containing the data of books borrowed by the user
     */
    public function borrowsByUser(){
        $conditions = ['user_id' => auth()->user()->id, 'return_date' => null];
        $relations = ['book'];
        return $this->borrowRepository->findWithConditions($conditions, $relations);
    }

    /**
     * Retrieves the current borrower of the book specified by the uuid.
     *
     * @param string $uuid The uuid of the book
     *
     * @return array The response array containing the data of the current borrower
     */
    public function bookBorrower(string $uuid){
        $book = $this->bookService->getBook($uuid);
        if(!$book) {
            $response['success'] = false;
            $response['msg'] = 'Book not found';
            $response['status'] = 404;
            $response['data'] = null;
        }else {
            $conditions = ['book_id' => $book->id, 'return_date' => null];
            $relations = ['user'];
            $borrower = $this->borrowRepository->findWithConditions($conditions, $relations, true);
            $response['success'] = true;
            $response['msg'] = 'Current Book borrower fetched successfully';
            $response['status'] = 200;
            $response['data'] = $borrower;
        }
        return $response;
    }

    /**
     * Returning a book.
     *
     * @param string $uuid The uuid of the borrow record
     *
     * @return array The response array containing the data of the returned book
     */
    public function returnBook(string $uuid){
        $borrowRecord = $this->borrowRepository->findByUuid($uuid);
        if(!$borrowRecord) {
            $response['success'] = false;
            $response['msg'] = 'Borrow record not found';
            $response['status'] = 404;
        }elseif($borrowRecord->user_id != auth()->user()->id) {
            $response['success'] = false;
            $response['msg'] = 'You are not authorized to return this book';
            $response['status'] = 403;
        }elseif($borrowRecord->due_date < now()) {
            $response['success'] = false;
            $response['msg'] = 'Due date exceeded, kindely visit the library to submit with late fee';
            $response['status'] = 400;
        }else{
            $book = $this->bookService->getBookById($borrowRecord->book_id);
            $book->update(['available' => StatusEnum::AVAILABLE->value]);
            $borrowRecord->update(['return_date' => now()]);
            $response['success'] = true;
            $response['msg'] = 'Book returned successfully';
            $response['status'] = 200;
            $response['data'] = $borrowRecord;
        }
        return $response;
    }

    /**
     * Retrieves all overdue books.
     *
     * @return \Illuminate\Support\Collection The collection of overdue borrow records, including the user and book relations.
     */
    public function overdueBooks(){
        $conditions = ['due_date' => ['<', now()], 'return_date' => null];
        $relations = ['user', 'book'];
        return $this->borrowRepository->findWithConditions($conditions, $relations);
    }

    /**
     * Retrieves all the books that have been returned.
     *
     * @return \Illuminate\Support\Collection The collection of returned borrow records, including the user and book relations.
     */
    public function allReturnedBooks(){
        $conditions = ['return_date' => ['<>', null]];
        $relations = ['user', 'book'];
        return $this->borrowRepository->findWithConditions($conditions, $relations);
    }
}
