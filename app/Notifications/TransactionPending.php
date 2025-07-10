<?php

namespace App\Notifications;

use App\Models\Transaction; // Impor model Transaction
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionPending extends Notification implements ShouldQueue // Tambahkan ShouldQueue jika ingin pakai Queue
{
    use Queueable;

    public $transaction;

    /**
     * Create a new notification instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Akan disimpan ke tabel 'notifications'
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'transaction_pending',
            'title' => 'Pembayaran Anda Tertunda!',
            'message' => 'Segera lakukan pembayaran untuk pesanan Anda dengan kode ' . $this->transaction->code . '. Status pembayaran Anda saat ini adalah ' . ucfirst($this->transaction->payment_status) . '.',
             'action_url' => route('resend-payment-link', ['code' => $this->transaction->code]), // Rute baru
            'icon' => asset('assets/images/icons/notification-pending.svg'), // Icon untuk notif pending
        ];
    }
}