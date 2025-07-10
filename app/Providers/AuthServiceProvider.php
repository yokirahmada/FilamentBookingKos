<?php

namespace App\Providers;

use App\Models\User; // Pastikan ini diimpor
use Illuminate\Auth\Notifications\ResetPassword; // Pastikan ini diimpor
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk debugging

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
   public function boot(): void
    {
        // Pastikan ini meng-override Mailable ResetPassword bawaan Laravel
        ResetPassword::toMailUsing(function (User $user, string $token) {
            return (new \App\Mail\CustomResetPasswordMail($token, $user->email))->to($user->email);
        });
    }
}