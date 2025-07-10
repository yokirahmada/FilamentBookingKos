<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Mail\Mailables\Address; // Tidak perlu jika tidak spesifik from
// use Illuminate\Support\Facades\Log; // Tidak perlu jika tidak ada debugging di sini


class CustomResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct(string $token, string $email = '')
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.reset-password', // HTML view kustom Anda
            text: 'mail.reset-password-text', // <<< PENTING: Plain text view kustom Anda
            with: [
                'url' => route('password.reset', ['token' => $this->token, 'email' => $this->email]),
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
                'email' => $this->email,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}