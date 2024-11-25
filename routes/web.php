<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Api\v1\PaymentController;
use Illuminate\Support\Facades\Log;

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
    return view('login');
})->name('login');


Route::get('/subscribeform/{intent}', function (string $intent) {
    return view('payment.subscription', ['intent' => $intent]);
});

Route::get('borrow/{id}/pay-late-fee', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::get('/create-checkout-session/{borrow}', [PaymentController::class, 'createCheckoutSession'])->name('checkout.session');

Route::get('/payment-success', function () {
    return 'Payment Successful, book returned to library';
})->name('payment.success');

Route::get('/payment-cancel', function () {
    return 'Payment Canceled, book not returned to library';
})->name('payment.cancel');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->name('admin.dashboard');

// Route::get('/checkout', function (Request $request) {
//     $stripePriceId = 'price_1QKd91BdsatuffklMo6SoDQd';
 
//     $quantity = 1;
//     $user = User::where('email', 'kishan@gmail.com')->first();
//     $user->invoiceFor('One-time Book Purchase', 10);
//     return $user->checkout([$stripePriceId => $quantity], [
//         'success_url' => route('checkout-success'),
//         'cancel_url' => route('checkout-cancel'),
//     ]);
// })->name('checkout');

// Route::get('/invoices', function () {
//     $user = User::where('email', 'kishan@gmail.com')->first(); // or use `Auth::user()` for logged-in users
//     $invoices = $user->invoices()->first();
//     if ($invoices) {
//         return $user->downloadInvoice($invoices->id, [
//             'vendor' => 'Your Library Name', // Customize vendor details
//             'product' => 'Book Purchase'
//         ]);
//     }

//     return response()->json(['message' => 'Invoice not found'], 404);
// })->name('invoices');
 
// Route::view('/checkout/success', 'checkout.success')->name('checkout-success');
// Route::view('/checkout/cancel', 'checkout.cancel')->name('checkout-cancel');
