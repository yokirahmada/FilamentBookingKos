<?php

namespace App\Notifications;

use App\Models\Transaction; // Impor model Transaction
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransactionSuccess extends Notification implements ShouldQueue // Tambahkan ShouldQueue jika ingin pakai Queue
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
            'type' => 'transaction_success',
            'title' => 'Pembayaran Anda Berhasil!',
            'message' => 'Terima kasih telah melakukan pembayaran untuk pesanan Anda dengan kode ' . $this->transaction->code . '. Detail lengkap bisa dilihat.',
            'action_url' => route('show-booking-details-by-code', ['code' => $this->transaction->code]), // Link ke detail transaksi
            'icon' => asset('assets/images/icons/notification-success.svg'), // Icon untuk notif sukses
        ];
    }
}