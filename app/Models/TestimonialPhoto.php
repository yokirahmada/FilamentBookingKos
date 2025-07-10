<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Testimonial;

class TestimonialPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'testimonial_id',
        'image_path',
    ];

    // Relasi ke Testimonial
    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }
}