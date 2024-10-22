<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\BorrowService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    use JsonResponseTrait;

    public function __construct(
        protected BorrowService $borrowService
    ){
    }

    public function index(Request $request){
        try {
            $data = $this->borrowService->allCurrentBorrows();
            return $this->successResponse($data, 'Book borrowings retrieved successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function borrow(Request $request, string $uuid){
        try {
            $response = $this->borrowService->borrow($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not borrowed', $response['msg'], $response['status']);
            }
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function borrowsByUser(Request $request){
        try {
            $data = $this->borrowService->borrowsByUser();
            return $this->successResponse($data, 'Books borrowed by user retrieved successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function bookBorrower(string $uuid){
        try {
            $response = $this->borrowService->bookBorrower($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not borrowed', $response['msg'], $response['status']);
            }
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function returnBook(Request $request, string $uuid){
        try {
            $response = $this->borrowService->returnBook($uuid);
            if(!$response['success']) {
                return $this->errorResponse('Book not returned', $response['msg'], $response['status']);
            }
            return $this->successResponse($response['data'], $response['msg'], $response['status']);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    public function overdueBooks(){
        try {
            $data = $this->borrowService->overdueBooks();
            return $this->successResponse($data, 'Overdue books retrieved successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }
}
