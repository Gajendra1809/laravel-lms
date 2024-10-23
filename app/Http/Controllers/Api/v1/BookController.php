<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\LoggingService;
use App\Http\Requests\BookRequests\BookCreateRequest;
use App\Http\Requests\BookRequests\BookUpdateRequest;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Traits\JsonResponseTrait;
use App\Models\Book;

class BookController extends Controller
{
    use JsonResponseTrait;

    /**
     * Constructor for the BookController class.
     *
     * @param BookService $BookService The book service dependency.
     */
    public function __construct(
        protected BookService $bookService,
        protected LoggingService $logService
       ){
       }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $this->bookService->all();
            return $this->successResponse($data, 'Books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Books not retrieved', $th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookCreateRequest $request)
    {
        try {
            $this->authorize('create', Book::class);
            $data = $request->validated();
            $response = $this->bookService->create($data);
            $this->logService->logInfo('book', 'Book added', $response);
            return $this->successResponse($response, 'Book created successfully', 201);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Book not created', $th->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $uuid)
    {
        try {
            $data = $this->bookService->getBook($uuid);
            if(!$data) {
                return $this->errorResponse('Book not retrieved', 'Uuid not found', 404);
            }
            return $this->successResponse($data, 'Book retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Book not retrieved', $th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateRequest $request, string $uuid)
    {
        try {
            $data = $request->validated();
            $response = $this->bookService->updateByUuid($uuid, $data);
            if(!$response) {
                return $this->errorResponse('Book not updated', 'Book not found', 404);
            }
            $this->logService->logInfo('book', 'Book updated', $response);
            return $this->successResponse($response, 'Book updated successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Book not updated', $th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $response = $this->bookService->deleteByUuid($id);
            if(!$response) {
                return $this->errorResponse('Book not deleted', 'Book not found', 404);
            }
            $this->logService->logInfo('book', 'Book deleted', $response);
            return $this->successResponse($response, 'Book deleted successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Book not deleted', $th->getMessage(), 500);
        }
    }

    protected function search(Request $request){
        try {
            $data = $this->bookService->search($request->search);
            return $this->successResponse($data, 'Books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    protected function exportAllBooks(Request $request){
        try {
            return $this->bookService->exportAllBooks();
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }

    protected function importBooks(Request $request){
        try {
            $this->authorize('create', Book::class);
            $response = $this->bookService->importBooks($request);
            if(!$response) {
                return $this->errorResponse('Books not imported', '', 404);
            }
            $this->logService->logInfo('book', 'Books imported');
            return $this->successResponse($response, 'Books imported successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }
}
