{{-- resources/views/auth/reset-password.blade.php --}}

@extends('layouts.app') {{-- Menggunakan layout app custom Anda --}}

@section('content')
    {{-- Background Hijau Muda seperti di Homepage --}}
    <div id="Background" class="absolute top-0 w-full h-[280px] rounded-bl-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
    </div>

    {{-- TopNav (seperti di halaman login/register/dashboard) --}}
    <div id="TopNav" class="relative flex items-center justify-center px-5 mt-[60px] text-center w-full">
        <h2 class="font-bold text-[30px] leading-[45px] text-ngekos-black">
            Reset Password Anda
        </h2>
    </div>

    <div class="flex items-center justify-center min-h-screen py-12 px-5 mt-[20px] relative z-10">
        <div class="max-w-md w-full rounded-[30px] p-5 gap-4 bg-white shadow-[0px_12px_30px_0px_#0000000D] border border-[#F1F2F6] overflow-hidden">

            <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-4">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Alamat Email</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis email Anda">
                    </label>
                    @error('email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Password Baru</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Minimal 8 karakter">
                    </label>
                    @error('password')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Konfirmasi Password Baru</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Konfirmasi password baru">
                    </label>
                    @error('password_confirmation')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="flex w-full justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection