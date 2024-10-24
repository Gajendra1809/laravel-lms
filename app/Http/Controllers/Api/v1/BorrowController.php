<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\BorrowService;
use App\Services\LoggingService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    use JsonResponseTrait;

    /**
     * Constructs a new instance of the BorrowController, injecting
     * the required BorrowService and LoggingService via the constructor.
     *
     * @param BorrowService $borrowService The borrow service to be used.
     * @param LoggingService $logService The logging service to be used.
     */
    public function __construct(
        protected BorrowService $borrowService,
        protected LoggingService $logService
    ){
    }

    /**
     * Get all current book borrowings.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        try {
            $data = $this->borrowService->allCurrentBorrows();
            return $this->successResponse($data, 'Book borrowings retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Borrow a book.
     *
     * @param Request $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function borrow(Request $request, string $uuid){
        try {
            $response = $this->borrowService->borrow($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not borrowed', $response['msg'], $response['status']);
            }
            $this->logService->logInfo('borrowing', 'Book borrowed', $response['data']);
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Retrieves the books borrowed by the user.
     *
     * @param Request $request The HTTP request
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data of books borrowed by the user
     */
    public function borrowsByUser(Request $request){
        try {
            $data = $this->borrowService->borrowsByUser();
            return $this->successResponse($data, 'Books borrowed by user retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Retrieves the current borrower of the book specified by the uuid.
     *
     * @param Request $request The HTTP request
     * @param string $uuid The uuid of the book
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data of the current borrower
     */
    public function bookBorrower(Request $request, string $uuid){
        try {
            $response = $this->borrowService->bookBorrower($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not borrowed', $response['msg'], $response['status']);
            }
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Returning a book.
     *
     * @param Request $request The HTTP request
     * @param string $uuid The uuid of the borrow record
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data of the returned book
     */
    public function returnBook(Request $request, string $uuid){
        try {
            $response = $this->borrowService->returnBook($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not returned', $response['msg'], $response['status']);
            }
            $this->logService->logInfo('borrowing', 'Book returned', $response['data']);
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Retrieves all overdue books.
     *
     * @param Request $request The HTTP request
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data of overdue books
     */
    public function overdueBooks(Request $request){
        try {
            $data = $this->borrowService->overdueBooks();
            return $this->successResponse($data, 'Overdue books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Retrieves all books that have been returned.
     *
     * @param Request $request The HTTP request
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the data of returned books
     */
    public function allReturnedBooks(Request $request){
        try {
            $data = $this->borrowService->allReturnedBooks();
            return $this->successResponse($data, 'Returned books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }
}
