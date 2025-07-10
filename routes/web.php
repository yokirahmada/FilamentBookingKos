<?php

// Pastikan semua 'use' statement ini ada di bagian paling atas file
use App\Mail\CustomerServiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BoardingHouseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;

// --- Rute Umum Website Booking Kos Anda (Tidak Memerlukan Login) ---
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/boarding-house/{slug}', [BoardingHouseController::class, 'show'])->name('show-boarding-house-by-slug');
Route::get('/boarding-house/{slug}/rooms', [BoardingHouseController::class, 'rooms'])->name('show-rooms-list-in-boarding-house-by-slug');
Route::get('/boarding-houses', [BoardingHouseController::class, 'boardingHouses'])->name('show-boarding-houses');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('show-boarding-house-by-category-slug');
Route::get('/city/{slug}', [CityController::class, 'show'])->name('show-boarding-house-by-city-slug');
Route::get('/find', [BoardingHouseController::class, 'find'])->name('find-boarding-house');
Route::get('/find-results', [BoardingHouseController::class, 'findResults'])->name('find-results-boarding-house');

// Ini adalah rute GET untuk menampilkan form check-booking
Route::get('/check-booking', [BookingController::class, 'checkBooking'])->name('check-booking');
// Ini adalah rute POST untuk form check-booking
Route::post('/check-booking', [BookingController::class, 'showBookingDetails'])->name('show-booking-details');

// Ini adalah rute GET baru untuk detail booking berdasarkan kode, ideal untuk link dari dashboard
// PENTING: Ini tidak di bawah /akun jika Anda ingin URL-nya /booking-details/{code}
Route::get('/booking-details/{code}', [BookingController::class, 'showBookingDetailsByCode'])->middleware('auth')->name('show-booking-details-by-code');


Route::get('/customer-service', [HomeController::class, 'customerService'])->name('customer-service');
Route::post('/customer-service', [HomeController::class, 'customerServiceMailProcess'])->name('customer-service-mail');


// --- Pastikan fallback berada di PALING BAWAH dari semua rute umum ---
Route::fallback(function () {
    return redirect()->route('home');
});

// --- Rute Khusus Pengguna (Memerlukan Login) ---
Route::prefix('akun')->group(function () { // Mulai prefix /akun
    require __DIR__.'/auth.php'; // Ini mencakup /akun/login, /akun/register, /akun/logout

    // Dashboard user
    Route::get('/dashboard', [App\Http\Controllers\BookingController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

    // Grup rute yang memerlukan autentikasi (user sudah login)
    Route::middleware('auth')->group(function () { // Mulai middleware auth (semua di sini butuh login)
        // Profil user
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Notifikasi
        Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

        // Rute booking yang memerlukan login (ini tetap di bawah 'akun' dan 'auth')
        Route::get('/booking/{slug}', [BookingController::class, 'booking'])->name('show-booking');
        Route::get('/booking/{slug}/info', [BookingController::class, 'information'])->name('show-booking-information');
        Route::post('/booking/{slug}/save', [BookingController::class, 'save'])->name('save-booking-information');
        Route::get('/booking/{slug}/checkout', [BookingController::class, 'checkout'])->name('booking-checkout');
        Route::post('/booking/{slug}/payment', [BookingController::class, 'payment'])->name('payment-process'); // <<< HANYA SATU KALI DI SINI >>>

        // Rute untuk melanjutkan pembayaran dari notifikasi pending
        Route::get('/resend-payment-link/{code}', [BookingController::class, 'resendPaymentLink'])->name('resend-payment-link');
        Route::post('/submit-testimonial', [TestimonialController::class, 'store'])->name('submit-testimonial');


    }); // Akhir dari Route::middleware('auth')->group()


    // Rute transaction-success (di bawah /akun, tidak harus auth jika hanya menampilkan status)
    // Jika Anda ingin user harus login untuk melihat halaman ini, pindahkan ke dalam group middleware 'auth' di atas
    Route::get('/transaction-success', [BookingController::class, 'transactionSuccess'])->name('transaction-success');

    // Jika Anda punya rute untuk error dan pending dari callback Midtrans, definisikan di sini juga:
    // Route::get('/transaction-failed', function () { return view('pages.booking.transaction-failed'); })->name('transaction-failed');
    // Route::get('/transaction-pending', function () { return view('pages.booking.transaction-pending'); })->name('transaction-pending');

}); // Akhir dari Route::prefix('akun')