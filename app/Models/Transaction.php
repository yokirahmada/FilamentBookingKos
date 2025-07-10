<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code',
        'user_id',
        'boarding_house_id',
        'room_id',
        'payment_method',
        'payment_status',
        'start_date',
        'duration',
        'total_amount',
        'transaction_date',
         'midtrans_redirect_url',
    ];

     protected $casts = [
        'start_date' => 'date',
        'transaction_date' => 'date',
        // Pastikan jika ada perubahan tipe data di migrasi, cocokkan di sini
    ];

      public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function boardingHouse():BelongsTo{
        return $this->belongsTo(BoardingHouse::class);
    }

    public function room():BelongsTo{
        return $this->belongsTo(Room::class);
    }
}
