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
     * Constructs a new instance of the BookController, injecting
     * the required BookService and LoggingService via the constructor.
     *
     * @param BookService $bookService The book service to be used.
     * @param LoggingService $logService The logging service to be used.
     */
    public function __construct(
        protected BookService $bookService,
        protected LoggingService $logService
       ){
       }

    /**
     * Returns a list of all books.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $data = $this->bookService->all();
            return $this->successResponse($data, 'Books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }
   
    /**
     * Stores a newly created book in storage.
     *
     * @param  \App\Http\Requests\BookRequests\BookCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
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
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Displays the specified book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
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
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Update the specified book in storage.
     *
     * @param  \App\Http\Requests\BookRequests\BookUpdateRequest  $request
     * @param  string  $uuid
     *
     * @return \Illuminate\Http\JsonResponse
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
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Delete the specified book from storage.
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
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
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Search for books based on a query data.
     *
     * @param Request $request The search request containing the query data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function search(Request $request){
        try {
            $data = $this->bookService->search($request->search);
            return $this->successResponse($data, 'Books retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Export all books to Excel.
     *
     * @param Request $request The export request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function exportAllBooks(Request $request){
        try {
            return $this->bookService->exportAllBooks();
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    /**
     * Import books from Excel into the database.
     *
     * @param Request $request The import request containing the Excel file.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }
}
