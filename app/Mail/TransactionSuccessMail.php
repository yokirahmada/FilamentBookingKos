<?php

namespace App\Mail;

use App\Models\Transaction; // Impor model Transaction
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction; // Properti untuk mengakses data transaksi di view

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction) // Menerima objek Transaction
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
                return new Envelope(
            subject: 'Konfirmasi Pembayaran Anda Berhasil untuk Order ID: ' . $this->transaction->code,
        );

    }

    /**
     * Get the message content definition.
     */
public function content(): Content
{
    return new Content(
        markdown: 'mail.transaction-success', // Perhatikan penggunaan `markdown:` sebagai argumen bernama
        with: [
            'transaction' => $this->transaction,
        ],
    );
}

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}