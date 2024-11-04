<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('borrow/{id}/pay-late-fee', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::get('/create-checkout-session/{borrow}', [PaymentController::class, 'createCheckoutSession'])->name('checkout.session');

Route::get('/payment-success', function () {
    return 'Payment Successful, book returned to library';
})->name('payment.success');

Route::get('/payment-cancel', function () {
    return 'Payment Canceled, book not returned to library';
})->name('payment.cancel');


