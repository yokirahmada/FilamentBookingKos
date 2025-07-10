<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini diimpor jika menggunakan HasFactory
use App\Models\TestimonialPhoto; // Pastikan ini diimpor
use App\Models\BoardingHouse; // Pastikan ini diimpor
use App\Models\User; // Pastikan ini diimpor


class Testimonial extends Model
{
    use SoftDeletes;
protected $fillable = [
    'boarding_house_id',
    'user_id', // Pastikan ini ada
    'name',    // Pastikan ini ada
    'content',
    'rating',
    'is_approved', // <<< TAMBAHKAN INI
];

    public function boardingHouse():BelongsTo{
        return $this->belongsTo(BoardingHouse::class);
    }

        public function user(): BelongsTo // Jika user_id ada dan digunakan
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(TestimonialPhoto::class);
    }
}
