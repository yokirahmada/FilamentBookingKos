<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel; 
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser // Ini sudah benar
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'address', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    // Metode yang sudah ada dan benar
    public function canAccessFilament(): bool
    {
        return $this->role === 'admin';
    }

    // <<< TAMBAHKAN METODE BARU INI >>>
    public function canAccessPanel(Panel $panel): bool
    {
        // Jika Anda hanya punya satu panel admin, Anda bisa langsung mengembalikan logika role.
        // Jika Anda punya beberapa panel, Anda bisa memeriksa $panel di sini.
        return $this->role === 'admin';

        // Contoh jika Anda punya beberapa panel dan ingin otorisasi spesifik:
        // if ($panel === 'admin') {
        //     return $this->role === 'admin';
        // }
        // return false;
    }

    // Helper method (opsional)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

     public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

}