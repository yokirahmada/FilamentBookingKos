<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\ShowBookingDetailsRequest;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\BoardingHouseRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tetap pakai Log untuk debugging umum
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionConfirmationMail;
use App\Models\Room; // Pastikan ini diimpor
use App\Models\BoardingHouse;
use App\Models\Testimonial; 


class BookingController extends Controller
{
    private BoardingHouseRepositoryInterface $boardingHouseRepository;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(BoardingHouseRepositoryInterface $boardingHouseRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->boardingHouseRepository = $boardingHouseRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk melihat dashboard.');
        }

        $transactions = Auth::user()->transactions()
                                ->with(['boardingHouse.city', 'room.images'])
                                ->orderBy('created_at', 'desc')
                                ->get();

        return response()->view('dashboard', compact('transactions'));
    }

    public function checkBooking()
    {
        return response()->view('pages.booking.check-booking');
    }

 public function booking(Request $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk melakukan pemesanan.');
        }

        $requestedRoomData = $request->only(['room_id']);
        Log::info('BookingController@booking: Request data received for room_id: ' . json_encode($requestedRoomData));

        if (empty($requestedRoomData) || !isset($requestedRoomData['room_id'])) {
            Log::error('BookingController@booking: room_id is missing from request data or empty array received.');
            return redirect()->back()->with('error', 'Silakan pilih kamar yang akan dipesan.');
        }

        $roomId = $requestedRoomData['room_id'];
        $room = Room::find($roomId);

        if (!$room) {
            Log::error('BookingController@booking: Room with ID ' . $roomId . ' not found.');
            return redirect()->back()->with('error', 'Kamar tidak ditemukan.');
        }
        if ($room->is_available == 0) {
            Log::warning('BookingController@booking: Room ' . $room->id . ' is not available.');
            return redirect()->back()->with('error', 'Kamar ini tidak tersedia untuk dipesan.');
        }

        // $pendingTransaction = Transaction::where('room_id', $roomId)
        //                                 ->whereIn('payment_status', ['pending', 'challenge'])
        //                                 ->where('created_at', '>', now()->subMinutes(30))
        //                                 ->first();
        // if ($pendingTransaction) {
        //     Log::warning('BookingController@booking: Room ' . $roomId . ' has a pending transaction.');
        //     return redirect()->back()->with('error', 'Kamar ini sedang dalam proses pemesanan. Silakan pilih kamar lain atau coba beberapa saat lagi.');
        // }

        // <<< TAMBAHKAN INI: Pastikan boarding_house_id juga disimpan ke sesi >>>
        $boardingHouse = BoardingHouse::where('slug', $slug)->first(); // Ambil BoardingHouse dari slug
        if (!$boardingHouse) {
            Log::error('BookingController@booking: BoardingHouse not found for slug: ' . $slug);
            return redirect()->back()->with('error', 'Kos tidak ditemukan.');
        }
        $requestedRoomData['boarding_house_id'] = $boardingHouse->id; // Tambahkan boarding_house_id ke data

        // Simpan data lengkap ke sesi (room_id dan boarding_house_id)
        $this->transactionRepository->saveTransactionDataToSession($requestedRoomData);
        return redirect()->route('show-booking-information', ['slug' => $slug]);
    }


    public function information($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk melihat informasi pemesanan.');
        }

        $boardingHouse = $this->boardingHouseRepository->getBoardingHouseBySlug($slug);
        $transactionData = $this->transactionRepository->getTransactionDataFromSession(); // array dari sesi

        if (!isset($transactionData['room_id'])) { // Jika sesi hilang atau tidak ada room_id
            return redirect()->route('home')->with('error', 'Data ruangan tidak ditemukan di sesi. Silakan mulai pemesanan dari awal.');
        }
        $room = $this->boardingHouseRepository->getBoardingHouseRoomById(id: $transactionData['room_id']); // Mengambil model Room
        if (!$room) { // Jika room tidak ditemukan di DB
            return redirect()->route('home')->with('error', 'Kamar tidak ditemukan di database. Silakan mulai pemesanan dari awal.');
        }
        return response()->view('pages.booking.information', compact('boardingHouse', 'transactionData', 'room'));
    }

    public function save(BookingRequest $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk menyimpan informasi.');
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id(); // Tambahkan user_id ke data

        Log::info('BookingController@save: Data after validation and user_id added: ' . json_encode($data));

        $this->transactionRepository->saveTransactionDataToSession($data); // Update sesi dengan data form

        Log::info('BookingController@save: Data saved to session. Redirecting to checkout.');

        return redirect()->route('booking-checkout', ['slug' => $slug]);
    }

    public function checkout($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk melanjutkan ke checkout.');
        }

        $boardingHouse = $this->boardingHouseRepository->getBoardingHouseBySlug($slug);
        $transactionData = $this->transactionRepository->getTransactionDataFromSession(); // Ambil data sesi
        if (!isset($transactionData['room_id'])) { // Jika sesi hilang atau tidak ada room_id
            return redirect()->route('home')->with('error', 'Data ruangan tidak ditemukan di sesi. Silakan mulai pemesanan dari awal.');
        }
        $room = $this->boardingHouseRepository->getBoardingHouseRoomById(id: $transactionData['room_id']); // Mengambil model Room
        if (!$room) { // Jika room tidak ditemukan di DB
            return redirect()->route('home')->with('error', 'Kamar tidak ditemukan di database. Silakan mulai pemesanan dari awal.');
        }

        return response()->view('pages.booking.checkout', compact('boardingHouse', 'transactionData', 'room'));
    }

    public function payment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk menyelesaikan pembayaran.');
        }

        $requestData = $request->all();
        $requestData['user_id'] = Auth::id(); // Tambahkan user_id ke data request
        //  dd($requestData);

        Log::info('BookingController@payment: Request data received: ' . json_encode($requestData));

        if (!isset($requestData['room_id'])) { // Validasi room_id dari request POST
            Log::error('BookingController@payment: room_id is missing from requestData.');
            return redirect()->back()->with('error', 'Data kamar tidak ditemukan. Silakan coba proses booking dari awal.');
        }

        if (!isset($requestData['boarding_house_id'])) { // Tambahkan validasi ini untuk debugging
        Log::error('BookingController@payment: boarding_house_id is missing from requestData.');
        return redirect()->back()->with('error', 'Data kos tidak ditemukan. Silakan coba proses booking dari awal.');
    }

        $roomId = $requestData['room_id'];
        $room = Room::find($roomId); // Cari kamar lagi

        // <<< VALIDASI FINAL KETERSEDIAAN KAMAR SEBELUM MEMBUAT TRANSAKSI MIDTRANS/FINAL SAVE >>>
        if (!$room || $room->is_available == 0) { // Cek ketersediaan lagi
            Log::error('Payment attempt for unavailable room: ' . $roomId);
            return redirect()->route('akun.dashboard')->with('error', 'Kamar ini telah dibooking atau tidak tersedia. Pembayaran tidak dapat dilanjutkan.');
        }
        // --- AKHIR VALIDASI KETERSEDIAAN KAMAR FINAL ---

        // Simpan data final (termasuk payment_method dari form) ke sesi
        $this->transactionRepository->saveTransactionDataToSession($requestData);

        // Dapatkan data lengkap dari sesi dan simpan ke database
        $transactionData = $this->transactionRepository->saveTransaction(
            $this->transactionRepository->getTransactionDataFromSession()
        );

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $transactionData->code,
                'gross_amount' => $transactionData->total_amount,
            ),
            'customer_details' => array(
                'first_name' => $transactionData->user->name,
                'email' => $transactionData->user->email,
                'phone' => $transactionData->user->phone_number,
            ),
            'callbacks' => array(
                'finish' => env('APP_URL') . '/akun/transaction-success',
                'error' => env('APP_URL') . '/akun/transaction-failed',
                'pending' => env('APP_URL') . '/akun/transaction-pending',
            ),
        );

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $transactionData->midtrans_redirect_url = $paymentUrl;
            $transactionData->save(); // Simpan URL redirect ke transaksi

            Log::info('New Midtrans URL created and saved for transaction: ' . $transactionData->code);
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap creation failed (initial): ' . $e->getMessage() . ' for transaction code: ' . $transactionData->code);
            if (str_contains($e->getMessage(), 'order_id has already been taken')) {
                 return redirect()->route('akun.dashboard')->with('info', 'Transaksi ini mungkin sudah diproses. Silakan cek detail transaksi Anda.');
            }
            return redirect()->route('akun.dashboard')->with('error', 'Gagal memuat halaman pembayaran. Error: ' . $e->getMessage());
        }
    }

    public function transactionSuccess(Request $request)
    {
        $orderId = $request->get('order_id');
        $transaction = null;
        $maxAttempts = 10;
        $delayMicroseconds = 250000;

        if ($orderId) {
            for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
                $transaction = $this->transactionRepository->getTransactionByCode($orderId);

                if ($transaction) {
                    Log::info('Transaction found on attempt ' . ($attempt + 1) . ' for order_id: ' . $orderId . ' . Code: ' . $transaction->code);
                    break;
                }

                usleep($delayMicroseconds);
                Log::info('Transaction not found on attempt ' . ($attempt + 1) . ' for order_id: ' . $orderId . '. Retrying...');
            }
        } else {
            Log::warning('No order_id received in transactionSuccess request.');
        }

        if (!$transaction) {
            Log::error('Transaction still not found after ' . $maxAttempts . ' attempts for order_id: ' . $orderId . '. Redirecting to home.');
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau ada masalah. Silakan cek riwayat transaksi Anda beberapa saat lagi.');
        }

        return response()->view('pages.booking.transaction-success', compact('transaction'));
    }

    public function showBookingDetails(ShowBookingDetailsRequest $request)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk melihat detail booking Anda.');
        }

        $transaction = $this->transactionRepository->getTransactionByCode($request->code);

        if (!$transaction || $transaction->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan atau Anda tidak memiliki akses.');
        }

        return response()->view('pages.booking.details-booking', [
            'transaction' => $transaction
        ]);
    }

    public function showBookingDetailsByCode(Request $request, $code)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk melihat detail booking Anda.');
        }

        $transaction = $this->transactionRepository->getTransactionByCode($code);

        if (!$transaction || $transaction->user_id !== Auth::id()) {
            return redirect()->route('akun.dashboard')->with('error', 'Data transaksi tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $hasUserReviewed = false;
        if (Auth::check() && $transaction) { // Pastikan user login dan transaksi ditemukan
            $hasUserReviewed = Testimonial::where('user_id', Auth::id())
                                          ->where('boarding_house_id', $transaction->boardingHouse->id)
                                          ->exists();
        }

        return response()->view('pages.booking.details-booking', [
            'transaction' => $transaction
        ]);
    }

    public function resendPaymentLink(Request $request, $code)
    {
        if (!Auth::check()) {
            return redirect()->route('akun.login')->with('error', 'Anda harus login untuk mengirim ulang link pembayaran.');
        }

        $transaction = $this->transactionRepository->getTransactionByCode($code);

        if (!$transaction || $transaction->user_id !== Auth::id()) {
            return redirect()->route('akun.dashboard')->with('error', 'Transaksi tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // <<< VALIDASI TAMBAHAN INI >>>
        if (!in_array($transaction->payment_status, ['pending', 'challenge'])) {
            Log::warning('Resend payment link attempted for non-pending/challenge transaction: ' . $transaction->code . ' with status: ' . $transaction->payment_status);
            // Redirect ke detail transaksi itu sendiri, atau ke dashboard
            return redirect()->route('show-booking-details-by-code', ['code' => $transaction->code])->with('info', 'Transaksi ini sudah tidak dalam status menunggu pembayaran. Status saat ini: ' . ucfirst($transaction->payment_status));
        }
        // <<< AKHIR VALIDASI TAMBAHAN >>>

        Log::info('Attempting resend payment link for transaction: ' . $transaction->code);
        Log::info('Existing midtrans_redirect_url: ' . ($transaction->midtrans_redirect_url ?? 'NULL'));

        if ($transaction->midtrans_redirect_url) {
            Log::info('Redirecting to existing Midtrans URL: ' . $transaction->midtrans_redirect_url);
            return redirect($transaction->midtrans_redirect_url);
        }

        // Jika sampai sini, berarti midtrans_redirect_url KOSONG
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $transaction->code,
                'gross_amount' => $transaction->total_amount,
            ),
            'customer_details' => array(
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
                'phone' => $transaction->user->phone_number,
            ),
            'callbacks' => array(
                'finish' => env('APP_URL') . '/akun/transaction-success',
                'error' => env('APP_URL') . '/akun/transaction-failed',
                'pending' => env('APP_URL') . '/akun/transaction-pending',
            ),
        );

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $transaction->midtrans_redirect_url = $paymentUrl;
            $transaction->save(); // Penting: Simpan URL baru ke transaksi

            Log::info('New Midtrans URL created and saved for transaction: ' . $transaction->code);
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap creation failed for ' . $transaction->code . ': ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->route('dashboard')->with('error', 'Gagal memuat halaman pembayaran. Error: ' . $e->getMessage());
        }
    }


}