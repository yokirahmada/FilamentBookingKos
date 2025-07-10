<?php

namespace App\Repositories;

use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Log; // <<< PASTIKAN BARIS INI ADA


class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionDataFromSession(): ?array
    {
        return Session::get('x-transaction-data');
    }

    public function saveTransactionDataToSession(array $data): void
    {
        // --- DEBUGGING INI SANGAT PENTING ---
        Log::info('TransactionRepository@saveTransactionDataToSession: Data received: ' . json_encode($data)); // Ini akan berfungsi sekarang
        if (empty($data)) {
            Log::error('TransactionRepository@saveTransactionDataToSession: Empty data array received.');
        }
        // --- AKHIR DEBUGGING ---

        $transaction = Session::get('x-transaction-data', []);

        foreach($data as $key => $value){
            $transaction[$key] = $value;
        }

        Session::put('x-transaction-data', $transaction);
    }

     public function saveTransaction(array $data): Transaction
    {
        $room = Room::find($data['room_id']);

        $transactionDataToSave = [
            'code' => $this->generateTransactionCode(),
            'user_id' => $data['user_id'],
            'boarding_house_id' => $data['boarding_house_id'],
            'room_id' => $data['room_id'],
            // <<< TAMBAHKAN FALLBACK INI >>>
            'payment_method' => $data['payment_method'] ?? 'full_payment', // Default jika tidak ada
            'payment_status' => $data['payment_status'] ?? 'pending',
            'start_date' => $data['start_date'],
            'duration' => $data['duration'],
            'total_amount' => $this->calculatePaymentAmount(
                                $this->calculateTotalAmount($room->price_per_month, $data['duration']),
                                // <<< PASTIKAN MENGGUNAKAN payment_method DARI $transactionDataToSave INI >>>
                                $data['payment_method'] ?? 'full_payment' // Gunakan fallback di sini juga
                              ),
            'transaction_date' => $data['transaction_date'] ?? now(),
        ];

        unset($transactionDataToSave['name']);
        unset($transactionDataToSave['email']);
        unset($transactionDataToSave['phone_number']);
        unset($transactionDataToSave['address']);

        $transaction = Transaction::create($transactionDataToSave);
        Session::forget('x-transaction-data');
        return $transaction;
    }
    public function generateTransactionCode(): string
    {
        return "KOS" . rand(100000, 999999);
    }

    public function calculateTotalAmount(int $pricePerMonth, int $duration): float
    {
        $subtotal = $pricePerMonth * $duration;
        $tax = $subtotal * 0.12;
        $insurance = $subtotal * 0.01;
        return $subtotal + $tax + $insurance;
    }

    public function calculatePaymentAmount(float $total, string $paymentMethod): float
    {
        return $paymentMethod === 'full_payment' ? $total : $total * 0.3;
    }

    public function getTransactionByCode(string $code): ?Transaction
    {
        return Transaction::where('code', $code)
                    ->with(['user', 'boardingHouse.city', 'room.images'])
                    ->first();
    }

    public function getTransactionByCodeEmailPhone(string $code, string $email, string $phone): ?Transaction
    {
        $transaction = Transaction::where('code', $code)->with('user')->first();

        if ($transaction && $transaction->user && $transaction->user->email === $email && $transaction->user->phone_number === $phone) {
            return $transaction;
        }

        return null;
    }
}