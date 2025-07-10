<?php
// app/Notifications/TransactionCanceledDueToLatePayment.php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log; // <<< PENTING: Impor Log


class TransactionCanceledDueToLatePayment extends Notification implements ShouldQueue
{
    use Queueable;

    public $transaction;
    public $reason;

    public function __construct(Transaction $transaction, string $reason = 'Terlambat membayar atau kamar sudah dibooking oleh pelanggan lain.')
    {
        $this->transaction = $transaction;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $actionUrl = route('show-booking-details-by-code', ['code' => $this->transaction->code]);

        // <<< DEBUGGING INI SANGAT PENTING >>>
        Log::info('Generating action_url for Canceled Notification: ' . $actionUrl . ' for transaction code: ' . $this->transaction->code);
        // dd($actionUrl); // <<< AKTIFKAN INI UNTUK MENGHENTIKAN EKSEKUSI DAN MELIHAT URL >>>
        // --- AKHIR DEBUGGING ---

        return [
            'type' => 'transaction_canceled',
            'title' => 'Pemesanan Anda Dibatalkan!',
            'message' => 'Pemesanan Anda dengan kode ' . $this->transaction->code . ' telah dibatalkan. Alasan: ' . $this->reason . '. Silakan cari kos lain.',
            'action_url' => $actionUrl, // Gunakan variabel yang sudah di-generate
            'icon' => asset('assets/images/icons/notification-canceled.svg'),
            'transaction_code' => $this->transaction->code,
            'transaction_id' => $this->transaction->id,
        ];
    }
}