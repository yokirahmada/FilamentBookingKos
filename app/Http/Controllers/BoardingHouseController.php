<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\BoardingHouseRepositoryInterface;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth; // Penting untuk Auth::check() dan Auth::id()
use App\Models\Testimonial; // Import model Testimonial

class BoardingHouseController extends Controller
{
    private CityRepositoryInterface $CityRepository;
    private CategoryRepositoryInterface $CategoryRepository;
    private BoardingHouseRepositoryInterface $BoardingHouseRepository;

    public function __construct(CityRepositoryInterface $CityRepository, CategoryRepositoryInterface $CategoryRepository, BoardingHouseRepositoryInterface $BoardingHouseRepository)
    {
        $this->CityRepository = $CityRepository;
        $this->CategoryRepository = $CategoryRepository;
        $this->BoardingHouseRepository = $BoardingHouseRepository;
    }

public function show($slug){
        $boardingHouse = $this->BoardingHouseRepository->getBoardingHouseBySlug($slug);
        // Eager load relasi testimonial dan foto-fotonya
        $boardingHouse->load(['rooms.images', 'testimonials.photos']); // Pastikan 'testimonials.photos' ada

        if (!$boardingHouse) {
            return redirect()->route('home')->with('error', 'Kos tidak ditemukan.');
        }

        $boardingHouse->load([
            'rooms.images',
            'testimonials' => function($query) {
                $query->where('is_approved', true)->with('photos'); // Hanya testimonial yang disetujui, dan load fotonya
            }
        ]);


        $hasUserReviewed = false;
        if (Auth::check()) {
            $hasUserReviewed = Testimonial::where('user_id', Auth::id())
                                          ->where('boarding_house_id', $boardingHouse->id)
                                          ->exists();
        }

        return response()->view('pages.boarding-house.show', compact('boardingHouse', 'hasUserReviewed'));
    }

    public function boardingHouses(Request $request){
        $boardingHouses = $this->BoardingHouseRepository->getAllBoardingHouses($request);

        return response()->view('pages.boarding-house.boarding-houses-list', ['boardingHouses' => $boardingHouses]);
    }
 public function rooms($slug){
        $boardingHouse = $this->BoardingHouseRepository->getBoardingHouseBySlug($slug); // Mengambil data kos

        // --- DEBUGGING INI SANGAT PENTING ---
        if (!$boardingHouse) {
            dd('DEBUG: Boarding house not found for slug: ' . $slug);
        }
        Log::info('DEBUG: BoardingHouseController@rooms - BoardingHouse loaded: ' . ($boardingHouse->name ?? 'N/A'));
        // --- AKHIR DEBUGGING ---


        // <<< PENTING: Gunakan metode ini untuk mendapatkan HANYA kamar yang tersedia >>>
        $availableRooms = $this->BoardingHouseRepository->getAvailableRoomsByBoardingHouseSlug($slug);

        // --- DEBUGGING INI SANGAT PENTING ---
        Log::info('DEBUG: BoardingHouseController@rooms - Available rooms count: ' . $availableRooms->count());
        // dd($availableRooms); // <<< AKTIFKAN INI UNTUK MELIHAT APA SAJA KAMAR YANG TERSEDIA >>>
        // --- AKHIR DEBUGGING ---

        return response()->view('pages.boarding-house.rooms', compact('boardingHouse', 'availableRooms'));
    }

    public function find(){
        $categories = $this->CategoryRepository->getAllCategories();
        $cities = $this->CityRepository->getAllCities();
        return response()->view('pages.boarding-house.find', compact(['categories', 'cities']));
    }

    public function findResults(Request $request){
        $boardingHouses = $this->BoardingHouseRepository->getAllBoardingHouses($request);
        return response()->view('pages.boarding-house.find-results', compact(['boardingHouses']));
    }

}
