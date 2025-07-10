{{-- resources/views/auth/register.blade.php --}}

@extends('layouts.app') {{-- Menggunakan layout app custom Anda --}}

@section('content')
    {{-- Background Hijau Muda seperti di Homepage --}}
    <div id="Background" class="absolute top-0 w-full h-[280px] rounded-bl-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
    </div>

    <div class="flex items-center justify-center min-h-screen py-12 px-5 mt-[60px] relative z-10"> {{-- Konten berada di atas background --}}
        <div class="max-w-md w-full rounded-[30px] p-5 gap-4 bg-white shadow-[0px_12px_30px_0px_#0000000D] border border-[#F1F2F6] overflow-hidden"> {{-- Menyesuaikan dengan gaya card Anda --}}
            <div class="flex flex-col gap-1 mb-[30px] text-center"> {{-- Menyesuaikan spacing dan alignment --}}
                <h2 class="font-bold text-[30px] leading-[45px] text-ngekos-black">
                    Daftar Akun Baru
                </h2>
                <p class="text-sm text-ngekos-grey">
                    Atau
                    <a href="{{ route('login') }}" class="font-semibold text-ngekos-orange hover:text-ngekos-black"> {{-- Menggunakan warna ngekos-orange --}}
                        login ke akun yang sudah ada
                    </a>
                </p>
            </div>

            <form class="flex flex-col gap-4" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="flex flex-col gap-2"> {{-- Gap 2 dari output CSS --}}
                    <p class="font-semibold">Nama Lengkap</p> {{-- Menggunakan p.font-semibold seperti di form booking --}}
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon"> {{-- Icon dari form booking --}}
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis nama Anda">
                    </label>
                    @error('name')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Alamat Email</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis email Anda">
                    </label>
                    @error('email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Nomor Telepon</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/call.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required autocomplete="tel"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis nomor telepon Anda">
                    </label>
                    @error('phone_number')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Alamat Lengkap</p>
                    <label class="flex items-center w-full rounded-[22px] p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300"> {{-- Rounded-[22px] untuk textarea --}}
                        <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <textarea name="address" id="address" required autocomplete="street-address"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Jalan contoh No. 123, Kota Anda">{{ old('address') }}</textarea>
                    </label>
                    @error('address')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Password</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon"> {{-- Icon kunci --}}
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Minimal 8 karakter">
                    </label>
                    @error('password')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Konfirmasi Password</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon"> {{-- Icon kunci --}}
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Konfirmasi password Anda">
                    </label>
                    @error('password_confirmation')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>


                <div>
                    <button type="submit" class="flex w-full justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300"> {{-- Tombol full width, ngekos-orange --}}
                        {{ __('Daftar') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @include('includes.navigation')
@endsection