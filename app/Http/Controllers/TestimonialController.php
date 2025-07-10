<?php
// app/Http/Controllers/TestimonialController.php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\TestimonialPhoto;
use App\Http\Requests\StoreTestimonialRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function store(StoreTestimonialRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();
        $boardingHouseId = $data['boarding_house_id'];

        $existingTestimonial = Testimonial::where('user_id', $userId)
                                         ->where('boarding_house_id', $boardingHouseId)
                                         ->first();
        if ($existingTestimonial) {
            Log::warning('User ' . $userId . ' already submitted a testimonial for boarding house ' . $boardingHouseId . '.');
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk kos ini.');
        }
        
        $data['user_id'] = $userId;
        if (empty($data['name']) && Auth::check()) {
            $data['name'] = Auth::user()->name;
        }

        try {
            $testimonial = Testimonial::create([
                'boarding_house_id' => $data['boarding_house_id'],
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'content' => $data['content'],
                'rating' => $data['rating'],
            ]);

            Log::info('TestimonialController@store: Main testimonial record created with ID: ' . $testimonial->id);

            // <<< DEBUGGING FILE UPLOAD AGGRESIF >>>
            Log::info('TestimonialController@store: Request hasFile("photos"): ' . ($request->hasFile('photos') ? 'TRUE' : 'FALSE'));
            // dd($request->all()); // UNCOMMENT INI UNTUK MELIHAT SEMUA DATA REQUEST (TERMASUK FILE)

            if ($request->hasFile('photos')) {
                $photos = $request->file('photos'); // Ini seharusnya array dari UploadedFile objects
                Log::info('TestimonialController@store: Number of photos detected by Laravel: ' . count($photos));

                foreach ($photos as $photoFile) {
                    Log::info('TestimonialController@store: Processing file ' . ($photoFile->getClientOriginalName() ?? 'N/A') . ', isValid: ' . ($photoFile->isValid() ? 'TRUE' : 'FALSE') . ', Size: ' . $photoFile->getSize());
                    // dd($photoFile->isValid()); // UNCOMMENT UNTUK CEK VALIDITAS FILE PER FILE

                    if ($photoFile->isValid()) {
                        try {
                            $path = $photoFile->store('testimonial_gallery', 'public'); // Simpan ke disk 'public'
                            Log::info('TestimonialController@store: File stored at: ' . $path);
                            TestimonialPhoto::create([
                                'testimonial_id' => $testimonial->id,
                                'image_path' => $path,
                            ]);
                            Log::info('Testimonial photo record created in DB for path: ' . $path);
                        } catch (\Exception $e) {
                            Log::error('TestimonialController@store: Storage or DB error for photo ' . ($photoFile->getClientOriginalName() ?? 'N/A') . ': ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
                            // dd('Error storing or creating photo record: ' . $e->getMessage()); // UNCOMMENT UNTUK MENANGKAP ERROR EARLY
                        }
                    } else {
                        Log::warning('TestimonialController@store: File ' . ($photoFile->getClientOriginalName() ?? 'N/A') . ' is not valid (isValid() returned false).');
                    }
                }
            } else {
                Log::info('TestimonialController@store: No "photos" array found in request or no valid files.');
            }
            // <<< AKHIR DEBUGGING UNGGAH FOTO >>>

            Log::info('Testimonial created successfully: ' . $testimonial->id . ' by user ' . $userId);
            return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim!');
        } catch (\Exception $e) {
            Log::error('TestimonialController@store: Failed to create testimonial (outer catch): ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal mengirim ulasan Anda. Silakan coba lagi.');
        }
    }
}