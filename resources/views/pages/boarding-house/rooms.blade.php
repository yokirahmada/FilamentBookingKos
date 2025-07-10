{{-- resources/views/pages/boarding-house/rooms.blade.php --}}

@extends('layouts.app')
@section('content')
<div id="Background"
            class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
        </div>
        <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
            <a href="{{ route('show-boarding-house-by-slug', ['slug' => $boardingHouse->slug]) }}"
                class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
                <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
            </a>
            <p class="font-semibold">Pilih kamar yang tersedia</p>
            <div class="dummy-btn w-12"></div>
        </div>

        <div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
            <div class="flex flex-col w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
                <div class="flex gap-4">
                    <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                        @if($boardingHouse->thumbnail)
                            <img src="{{ asset('storage/'. $boardingHouse->thumbnail) }}" class="w-full h-full object-cover" alt="icon">
                        @else
                            <img src="{{ asset('assets/images/default/no_thumbnail.jpg') }}" class="w-full h-full object-cover" alt="No Thumbnail">
                        @endif
                    </div>
                    <div class="flex flex-col gap-3 w-full">
                        <h1 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $boardingHouse->name }}</h1>
                        <hr class="border-[#F1F2F6]">
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">{{ $boardingHouse->city->name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">{{ $boardingHouse->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <form action="{{ route('show-booking', ['slug' => $boardingHouse->slug]) }}" class="relative flex flex-col gap-4 mt-5">
        <input type="hidden" name="boarding_house_id" value="{{ $boardingHouse->id }}" >
        <h2 class="font-bold px-5">Kamar yang tersedia</h2>
        <div id="RoomsContainer" class="flex flex-col gap-4 px-5">
            @forelse ($availableRooms as $room) {{-- <<< UBAH LOOP INI MENGGUNAKAN $availableRooms >>> --}}
                <label class="relative group">
                    <input type="radio" name="room_id" class="absolute top-1/2 left-1/2 -z-10 opacity-0" value="{{ $room->id }}" required>
                    <div
                        class="flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#91BF77] group-has-[:checked]:ring-2 group-has-[:checked]:ring-[#91BF77] transition-all duration-300">
                        <div class="flex w-[120px] h-[156px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                            <img src="{{ asset('storage/'. $room->images->first()->image) }}" class="w-full h-full object-cover"
                                alt="icon">
                        </div>
                        <div class="flex flex-col gap-3 w-full">
                            <h3 class="font-semibold text-lg leading-[27px]">{{ $room->name }}</h3>
                            <hr class="border-[#F1F2F6]">
                            <div class="flex items-center gap-[6px]">
                                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0"
                                    alt="icon">
                                <p class="text-sm text-ngekos-grey">{{ $room->capacity }} orang/kamar</p>
                            </div>
                            <div class="flex items-center gap-[6px]">
                                <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                                <p class="text-sm text-ngekos-grey">{{ $room->square_feet }} m<sup>2</sup></p>
                            </div>
                            <hr class="border-[#F1F2F6]">
                            <p class="font-semibold text-lg text-ngekos-orange">Rp {{ number_format($room->price_per_month, 0, ',', '.') }}<span
                                    class="text-sm text-ngekos-grey font-normal">/bulan</span></p>         
                        </div>
                    </div>
                </label>
            @empty
                <div class="text-center py-10 bg-white rounded-[30px] shadow-sm border border-[#F1F2F6]">
                    <p class="font-bold text-ngekos-black">Kamar Kos terisi penuh</p> {{-- <<< UBAH PESAN INI >>> --}}
                </div>
            @endforelse
        </div>

        <div id="BottomButton" class="relative flex w-full h-[98px] shrink-0">
            <div class="fixed bottom-[30px] w-full max-w-[640px] px-5 z-10">
                <button type="submit"
                    class="w-full rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white text-center">Lanjut pesan</button>
                </div>
            </div>
        </form>
@endsection