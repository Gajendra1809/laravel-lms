<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BookController;
use App\Http\Controllers\Api\v1\BorrowController;
use App\Http\Controllers\Api\v1\PaymentController;
use Faker\Provider\ar_EG\Payment;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User Authentication routes
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/login', [AuthController::class, 'login']);
Route::post('forget-password/request-token', [AuthController::class, 'requestToken']);
Route::post('forget-password/reset', [AuthController::class, 'resetPassword']);



// User Subscription routes
Route::post('/subscribe', [PaymentController::class, 'showSubscriptionForm'])->middleware('auth:api');
Route::post('/subscription/payment', [PaymentController::class, 'createSubscription'])->middleware('auth:api');

Route::middleware(['auth:api', 'check_subscription'])->group(function () {

    // User Management routes
    Route::post('/users/search', [UserController::class, 'search']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/show/{uuid}', [UserController::class, 'show'])->middleware('check_valid_uuid');
    Route::put('/users/{uuid}', [UserController::class, 'update'])->middleware('check_valid_uuid');
    Route::delete('/users/{uuid}', [UserController::class, 'destroy'])->middleware('check_valid_uuid');
    Route::get('/users/logout', [AuthController::class, 'logout']);

    // Book Management routes
    Route::get('/books/search', [BookController::class, 'search']);
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/show/{uuid}', [BookController::class, 'show'])->middleware('check_valid_uuid');
    Route::put('/books/{uuid}', [BookController::class, 'update'])->middleware('check_valid_uuid');
    Route::delete('/books/{uuid}', [BookController::class, 'destroy'])->middleware('check_valid_uuid');
    Route::post('/admin/import/books', [BookController::class, 'importBooks']);
    Route::get('/admin/export/books', [BookController::class, 'exportAllBooks']);
   

    // Borrowing Management routes

    // for users
    Route::get('/borrow/{uuid}', [BorrowController::class, 'borrow'])->middleware('check_valid_uuid');
    Route::get('/borrow/{uuid}/return', [BorrowController::class, 'returnBook'])->middleware('check_valid_uuid');
    Route::get('/borrows/myBorrows', [BorrowController::class, 'borrowsByUser']);

    // for admins
    Route::get('admin/borrowings', [BorrowController::class, 'index']);
    Route::get('admin/book/{uuid}/borrowings', [BorrowController::class, 'bookBorrower'])->middleware('check_valid_uuid');
    Route::get('admin/overdue', [BorrowController::class, 'overdueBooks']);
    ROute::get('admin/returned/books', [BorrowController::class, 'allReturnedBooks']);
    Route::get('admin/borrowHistory/{uuid}/user', [BorrowController::class, 'borrowHistoryByUser'])->middleware('check_valid_uuid');
    Route::get('admin/borrowHistory/{uuid}/book', [BorrowController::class, 'borrowHistoryByBook'])->middleware('check_valid_uuid');
    Route::get('admin/returnHistory/{uuid}/book', [BorrowController::class, 'returnHistoryByBook'])->middleware('check_valid_uuid');

});

    Route::get('graph/most-borrowed-books/{limit}', [BorrowController::class, 'mostBorrowed']);
    Route::get('graph/books-status', [BorrowController::class, 'booksAvailabilityCount']);
    Route::get('graph/weekly-active-borrowers', [UserController::class, 'weeklyActiveUsers']);
    Route::get('graph/longest-borrowed-books', [BorrowController::class, 'longestBorrowed']);


