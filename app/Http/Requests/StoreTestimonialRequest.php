<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'boarding_house_id' => ['required', 'exists:boarding_houses,id'],
            'user_id' => ['required', 'exists:users,id', 'in:' . Auth::id()],
            'photo' => ['nullable', 'image', 'max:1024'], // <<< TAMBAHKAN VALIDASI INI
            'name' => ['nullable', 'string', 'max:255'],    // <<< TAMBAHKAN VALIDASI INI
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:1000'],
        ];
    }
}