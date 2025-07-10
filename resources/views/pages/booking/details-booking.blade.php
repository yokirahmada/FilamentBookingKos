@extends('layouts.app')
@section('content')
<div id="Background" class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]"></div>
        <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
            <a href="{{ route('home') }}" class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
                <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
            </a>
            <p class="font-semibold">Detail Pemesanan Anda</p>
            <div class="dummy-btn w-12"></div>
        </div>
        <div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
            <div class="flex flex-col w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
                <div class="flex gap-4">
                    <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                        {{-- Gambar Kos --}}
                        @if($transaction->boardingHouse && $transaction->boardingHouse->thumbnail)
                            <img src="{{ asset('storage/'. $transaction->boardingHouse->thumbnail) }}" class="w-full h-full object-cover" alt="icon">
                        @else
                            <img src="{{ asset('assets/images/default/no_thumbnail.jpg') }}" class="w-full h-full object-cover" alt="No Thumbnail">
                        @endif
                    </div>
                    <div class="flex flex-col gap-3 w-full">
                        <p class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $transaction->boardingHouse->name }}</p>
                        <hr class="border-[#F1F2F6]">
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">{{ $transaction->boardingHouse->city->name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">{{ $transaction->boardingHouse->category->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <hr class="border-[#F1F2F6]">
                <div class="flex gap-4">
                    <div class="flex w-[120px] h-[138px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                        {{-- Gambar Kamar --}}
                        @if($transaction->room && $transaction->room->images->isNotEmpty() && $transaction->room->images->first()->image)
                            <img src="{{ asset('storage/'. $transaction->room->images->first()->image) }}" class="w-full h-full object-cover" alt="icon">
                        @else
                            <img src="{{ asset('assets/images/default/no_room_image.jpg') }}" class="w-full h-full object-cover" alt="No Room Image">
                        @endif
                    </div>
                    <div class="flex flex-col gap-3 w-full">
                        <p class="font-semibold text-lg leading-[27px]">{{ $transaction->room->name }}</p>
                        <hr class="border-[#F1F2F6]">
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">{{ $transaction->room->capacity }} person</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">{{ $transaction->room->square_feet }} m<sup>2</sup></p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/shopping-bag.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-ngekos-grey">Termasuk Bonus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
            <label class="relative flex items-center justify-between">
                <p class="font-semibold text-lg">Pelanggan</p>
                <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon">
                <input type="checkbox" class="absolute hidden">
            </label>
            <div class="flex flex-col gap-4 pt-[22px]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Nama Lengkap</p>
                    </div>
                    <p class="font-semibold">{{ $transaction->user->name ?? 'N/A' }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Email</p>
                    </div>
                    <p class="font-semibold">{{ $transaction->user->email ?? 'N/A' }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/call.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Nomor Telepon</p>
                    </div>
                    <p class="font-semibold">{{ $transaction->user->phone_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
            <label class="relative flex items-center justify-between">
                <p class="font-semibold text-lg">Pemesanan</p>
                <img src="{{ asset('assets/images/icons/arrow-up.svg') }}"
                    class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300"
                    alt="icon">
                <input type="checkbox" class="absolute hidden">
            </label>
            <div class="flex flex-col gap-4 pt-[22px]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/calendar.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">ID Pemesanan</p>
                    </div>
                    <p class="font-semibold">{{ $transaction->code }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/clock.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Durasi</p>
                    </div>
                    <p class="font-semibold">{{ $transaction->duration }} Bulan</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/calendar.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Dimulai pada</p>
                    </div>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat("D MMMMYYYY") }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/calendar.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Berakhir pada</p>
                    </div>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($transaction->start_date)->addMonths($transaction->duration)->isoFormat("D MMMMYYYY") }}</p>
                </div>
            </div>
        </div>
        @php
        $subtotal = $transaction->room->price_per_month * $transaction->duration;
        $tax = $subtotal * 0.12;
        $insurance = $subtotal * 0.01;
        $total = $subtotal + $tax + $insurance;
        $downpayment = $total * 0.3;
        @endphp
        <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] mx-5 mt-5 overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
            <label class="relative flex items-center justify-between">
                <p class="font-semibold text-lg">Informasi Pembayaran</p>
                <img src="{{ asset('assets/images/icons/arrow-up.svg') }}"
                    class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300"
                    alt="icon">
                <input type="checkbox" class="absolute hidden">
            </label>
            <div class="flex flex-col gap-4 pt-[22px]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/card-tick.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Jenis Pembayaran</p>
                    </div>
                    @if($transaction->payment_method === 'full_payment')
                        <p class="font-semibold">Bayar Lunas 100%</p>
                    @else
                        <p class="font-semibold">Bayar dimuka</p>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/receipt-2.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Harga Kos</p>
                    </div>
                    <p class="font-semibold">Rp {{ number_format($transaction->room->price_per_month, 0, ',', '.') }}</p> {{-- Ganti ke $transaction->room->price_per_month --}}
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/receipt-2.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Sub Total</p>
                    </div>
                    <p class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/receipt-disscount.svg') }}" class="w-6 h-6 flex shrink-0"
                            alt="icon">
                        <p class="text-ngekos-grey">PPN 12%</p>
                    </div>
                    <p class="font-semibold">Rp {{ number_format($tax, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/security-user.svg') }}" class="w-6 h-6 flex shrink-0"
                            alt="icon">
                        <p class="text-ngekos-grey">Asuransi</p>
                    </div>
                    <p class="font-semibold">Rp {{ number_format($insurance, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/receipt-text.svg') }}" class="w-6 h-6 flex shrink-0"
                            alt="icon">
                        <p class="text-ngekos-grey">Total Pembayaran</p>
                    </div>
                    @if($transaction->payment_method === 'full_payment')
                        <p class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</p>
                    @else
                        <p class="font-semibold">Rp {{ number_format($downpayment, 0, ',', '.') }}</p>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/security-card.svg') }}" class="w-6 h-6 flex shrink-0" alt="icon">
                        <p class="text-ngekos-grey">Status</p>
                    </div>
                @php
                    $statusText = 'Tidak Diketahui';
                    $statusColorClass = 'bg-gray-100 text-gray-800'; // Default styling

                    switch ($transaction->payment_status) {
                        case 'pending':
                        case 'challenge':
                            $statusText = 'Tertunda';
                            $statusColorClass = 'bg-ngekos-orange font-bold text-white'; // Menggunakan ngekos-orange
                            break;
                        case 'paid':
                        case 'settlement':
                        case 'success':
                            $statusText = 'Sukses';
                            $statusColorClass = 'bg-[#91BF77] font-bold text-white'; // Menggunakan warna sukses Anda
                            break;
                        case 'canceled':
                        case 'failed':
                        case 'deny':
                        case 'expired':
                            $statusText = 'Dibatalkan';
                            $statusColorClass = 'bg-red-100 text-red-800'; // Warna merah untuk batal
                            break;
                        default:
                            $statusText = ucfirst($transaction->payment_status); // Jika ada status lain, tampilkan apa adanya
                            $statusColorClass = 'bg-gray-100 text-gray-800';
                            break;
                        }
                    @endphp
                    <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] {{ $statusColorClass }}">
                        {{ $statusText }}
                    </p>
                    </div>
                </div>
            </div>


        <div id="BottomButton" class="relative flex w-full h-[98px] shrink-0">
            <div class="fixed bottom-[30px] w-full max-w-[640px] px-5 z-10">
                <a href="#" class="flex w-full justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white">Hubungi Pusat Pelayanan Kami</a>
            </div>
        </div>
    </form>


  <section class="flex flex-col gap-4 px-5 mt-[30px] pb-[120px]">
        <h2 class="font-bold text-lg">Berikan Ulasan Anda</h2>
        <form action="{{ route('submit-testimonial') }}" method="POST" class="flex flex-col gap-4 bg-white rounded-[30px] p-5 shadow-sm border border-[#F1F2F6]" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="boarding_house_id" value="{{ $transaction->boardingHouse->id }}">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            {{-- FOTO (Opsional) --}}
                    <div class="flex flex-col gap-2">
            <p class="font-semibold">Foto (Opsional)</p>
            <input type="file" name="photos[]" multiple accept="image/png, image/jpeg, image/jpg" {{-- <<< PASTIKAN NAME="PHOTOS[]" DAN MULTIPLE ADA >>> --}}
                class="appearance-none outline-none w-full rounded-full p-[14px_20px] bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
            @error('photos') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            @error('photos.*') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </div>
            {{-- NAMA (Otomatis dari user login, tapi bisa di-override jika admin input) --}}
            <div class="flex flex-col gap-2">
                <p class="font-semibold">Nama (Opsional)</p>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name ?? '') }}"
                    class="appearance-none outline-none w-full rounded-full p-[14px_20px] bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300"
                    placeholder="Nama Anda">
                @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <p class="font-semibold">Rating (1-5)</p>
                <input type="number" name="rating" min="1" max="5" value="{{ old('rating') }}" required
                    class="appearance-none outline-none w-full rounded-full p-[14px_20px] bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                @error('rating') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <p class="font-semibold">Ulasan Anda</p>
                <textarea name="content" rows="4" required
                    class="appearance-none outline-none w-full rounded-[22px] p-[14px_20px] bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300"
                        placeholder="Tulis ulasan Anda tentang kos ini">{{ old('content') }}</textarea>
                @error('content') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300">
                Kirim Ulasan
            </button>
        </form>
    </section>

    @push('scripts')
@endpush
 @include('includes.navigation')
@endsection