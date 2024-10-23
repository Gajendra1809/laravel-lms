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

    public function __construct(
        protected BorrowService $borrowService,
        protected LoggingService $logService
    ){
    }

    public function index(Request $request){
        try {
            $data = $this->borrowService->allCurrentBorrows();
            return $this->successResponse($data, 'Book borrowings retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

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
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function borrowsByUser(Request $request){
        try {
            $data = $this->borrowService->borrowsByUser();
            return $this->successResponse($data, 'Books borrowed by user retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function bookBorrower(Request $request, string $uuid){
        try {
            $response = $this->borrowService->bookBorrower($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not borrowed', $response['msg'], $response['status']);
            }
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

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
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function overdueBooks(Request $request){
        try {
            $data = $this->borrowService->overdueBooks();
            return $this->successResponse($data, 'Overdue books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function allReturnedBooks(Request $request){
        try {
            $data = $this->borrowService->allReturnedBooks();
            return $this->successResponse($data, 'Returned books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }
}
