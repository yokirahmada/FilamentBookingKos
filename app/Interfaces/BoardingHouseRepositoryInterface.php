<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Models\BoardingHouse; // Tambahkan ini jika belum ada
use Illuminate\Database\Eloquent\Collection; // Tambahkan ini jika belum ada

interface BoardingHouseRepositoryInterface{
    public function getAllBoardingHouses(Request $request);
    public function getPopularBoardingHouses($limit = 5);
    public function getBoardingHouseByCitySlug($slug);
    public function getBoardingHouseByCategorySlug($slug);
    public function getBoardingHouseBySlug($slug);
    public function getBoardingHouseRoomById($id);

    public function getAvailableRoomsByBoardingHouseSlug(string $slug): Collection;

}
