<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Twilio\Rest\Client; // Komentari Twilio jika tidak digunakan
use App\Mail\TransactionSuccessMail;
use Illuminate\Support\Facades\Mail; // Pastikan ini diimpor
// use App\Jobs\ProcessMidtransNotification; // Komentari jika tidak digunakan
use Illuminate\Support\Facades\Log; // Pastikan ini diimpor
use App\Notifications\TransactionCanceledDueToLatePayment;
use App\Notifications\TransactionPending; // Impor notifikasi pending
use App\Notifications\TransactionSuccess; // Impor notifikasi sukses
use App\Mail\AdminTransactionNotificationMail;
use App\Models\User;
use App\Models\Room;

class MidtransController extends Controller
{
    public function callback(Request $request){
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $serverKey = config('midtrans.serverKey');
        $hashed = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . $serverKey);

        if ($hashed !== $notification->signature_key) {
            Log::warning('Midtrans Notification: Invalid signature key for order_id: ' . $notification->order_id);
            return response()->json([
                'message' => 'Invalid signature key',
            ], 403);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        $transaction = Transaction::where('code', $orderId)->with('user')->first();

        if(!$transaction){
            Log::error('Midtrans Notification: Transaction not found for order_id: ' . $orderId);
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }

        if (!$transaction->user) {
            Log::error('Midtrans Notification Debug: User relation is NULL for transaction_id: ' . $transaction->id . '. Cannot send notification.');
        } else {
            Log::info('Midtrans Notification Debug: User found for transaction ' . $transaction->code . ': ' . $transaction->user->email);
        }


                switch ($transactionStatus) {
            case 'capture':
                if ($notification->payment_type == 'credit_card') {
                    if ($notification->fraud_status == 'challenge') {
                        $transaction->update(['payment_status' => 'challenge']);
                        if ($transaction->user) {
                            $transaction->user->notify(new TransactionPending($transaction));
                            Log::info('Notif pending dipicu untuk: ' . $transaction->code);
                        } else {
                            Log::warning('User null, skipped pending notification for: ' . $transaction->code);
                        }
                    } else {
                        // Status paid
                        $transaction->update(['payment_status' => 'paid']);
                        if ($transaction->user) {
                            $transaction->user->notify(new TransactionSuccess($transaction));
                            Log::info('Notif sukses dipicu untuk: ' . $transaction->code);
                        } else {
                            Log::warning('User null, skipped success notification for: ' . $transaction->code);
                        }

                        $room = Room::find($transaction->room_id);
                        if ($room) {
                            $room->update(['is_available' => 0]); // Set kamar tidak tersedia
                            Log::info('Room ' . $room->id . ' is_available set to 0 for transaction ' . $transaction->code);

                            // <<< LOGIKA AUTO-CANCEL TRANSAKSI LAMA YANG PENDING >>>
                            $this->cancelOldPendingTransactions($transaction);
                        } else {
                            Log::warning('Room not found for transaction ' . $transaction->code . ', cannot update availability.');
                        }
                    }
                }
                break;
            case 'settlement':
                // Status paid
                $transaction->update(['payment_status' => 'paid']);

                // Kirim Notifikasi Sukses In-App ke user yang baru bayar
                if ($transaction->user) {
                    $transaction->user->notify(new TransactionSuccess($transaction));
                    Log::info('Notif sukses in-app dipicu untuk: ' . $transaction->code);

                    // Kirim Email Konfirmasi ke Customer yang baru bayar
                    if ($transaction->user->email) {
                        try {
                            Mail::to($transaction->user->email)->send(new TransactionSuccessMail($transaction));
                            Log::info('Email konfirmasi CUSTOMER terkirim untuk transaksi: ' . $transaction->code);
                        } catch (\Exception $e) {
                            Log::error("Failed to send CUSTOMER email for order " . $orderId . ": " . $e->getMessage());
                        }
                    } else {
                        Log::warning('Skipping CUSTOMER email for transaction ' . $orderId . ': User email not found.');
                    }
                } else {
                    Log::warning('User null, skipped success notification (and email) for: ' . $transaction->code);
                }

                $room = Room::find($transaction->room_id);
                if ($room) {
                    $room->update(['is_available' => 0]); // Set kamar tidak tersedia
                    Log::info('Room ' . $room->id . ' is_available set to 0 for transaction ' . $transaction->code);

                    // <<< LOGIKA AUTO-CANCEL TRANSAKSI LAMA YANG PENDING >>>
                    $this->cancelOldPendingTransactions($transaction); // Panggil metode baru
                } else {
                    Log::warning('Room not found for transaction ' . $transaction->code . ', cannot update availability.');
                }

                // Kirim Email Notifikasi ke Admin
                $adminUsers = User::where('role', 'admin')->get();
                if ($adminUsers->isEmpty()) {
                    Log::warning('Skipping ADMIN email for transaction ' . $orderId . ': No admin users found in database.');
                } else {
                    foreach ($adminUsers as $admin) {
                        if ($admin->email) {
                            try {
                                Mail::to($admin->email)->send(new AdminTransactionNotificationMail($transaction));
                                Log::info('Email notifikasi ADMIN terkirim untuk transaksi: ' . $transaction->code . ' ke: ' . $admin->email);
                            } catch (\Exception $e) {
                                Log::error("Failed to send ADMIN email for order " . $orderId . " to " . $admin->email . ": " . $e->getMessage());
                            }
                        } else {
                            Log::warning('Skipping ADMIN email for user ' . $admin->id . ': Email not found.');
                        }
                    }
                }
                break;
            case 'pending':
                $transaction->update(['payment_status' => 'pending']);
                if ($transaction->user) {
                    $transaction->user->notify(new TransactionPending($transaction));
                    Log::info('Notif pending dipicu untuk: ' . $transaction->code);
                } else {
                    Log::warning('User null, skipped pending notification for: ' . $transaction->code);
                }
                break;
            case 'deny':
                $transaction->update(['payment_status' => 'failed']);
                break;
            case 'expire':
            $transaction->update(['payment_status' => 'expired']);
            break;
        case 'cancel':
            // <<< UBAH BARIS INI >>>
            $transaction->update(['payment_status' => 'canceled']); // Perbaiki sintaks array asosiatif
            break;
        default:
            // Ini sudah benar setelah perbaikan sebelumnya
            $transaction->update(['payment_status' => 'unknown']);
            break;
        }

        // <<< HANYA PERTAHANKAN SATU BARIS INI DI AKHIR METODE >>>
        return response()->json(['message' => 'Callback received successfully'], 200);
    }

 private function cancelOldPendingTransactions(Transaction $currentSuccessfulTransaction)
    {
        $conflictingTransactions = Transaction::where('room_id', $currentSuccessfulTransaction->room_id)
                                            ->where('id', '!=', $currentSuccessfulTransaction->id)
                                            ->whereIn('payment_status', ['pending', 'challenge'])
                                            ->get();

        Log::info('Found ' . $conflictingTransactions->count() . ' conflicting pending transactions for room ' . $currentSuccessfulTransaction->room_id . ' after successful payment of ' . $currentSuccessfulTransaction->code);

        foreach ($conflictingTransactions as $oldTransaction) {
            // Update status transaksi lama menjadi 'canceled'
            $oldTransaction->update(['payment_status' => 'canceled']);
            Log::info('Transaction ' . $oldTransaction->code . ' has been canceled due to new successful booking.');

            if ($oldTransaction->user) {
                // <<< PENTING: PASTIKAN PENGHAPUSAN NOTIFIKASI LAMA INI BERFUNGSI >>>
                // Hapus notifikasi pending yang spesifik untuk user dan transaksi ini
                $oldNotificationToDelete = $oldTransaction->user->notifications()
                    ->where('type', \App\Notifications\TransactionPending::class)
                    ->where('data->transaction_code', $oldTransaction->code) // Query berdasarkan kode transaksi di data
                    ->first(); // Ambil satu notifikasi yang cocok

                if ($oldNotificationToDelete) {
                    $oldNotificationToDelete->delete(); // Hapus notifikasi
                    Log::info('Deleted old pending notification for transaction: ' . $oldTransaction->code);
                } else {
                    Log::warning('No matching pending notification found to delete for transaction: ' . $oldTransaction->code);
                }

                // Kirim notifikasi pembatalan ke user pemilik transaksi lama
                $oldTransaction->user->notify(new \App\Notifications\TransactionCanceledDueToLatePayment(
                    $oldTransaction,
                    'Kamar telah dibooking oleh pelanggan lain.'
                ));
                Log::info('Notification sent for canceled transaction: ' . $oldTransaction->code . ' to ' . $oldTransaction->user->email);
            } else {
                Log::warning('Skipping cancellation notification for transaction ' . $oldTransaction->code . ': User not found.');
            }
        }
    }

}