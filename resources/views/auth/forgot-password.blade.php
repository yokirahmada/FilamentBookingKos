{{-- resources/views/auth/forgot-password.blade.php --}}

@extends('layouts.app') {{-- Menggunakan layout app custom Anda --}}

@section('content')
    {{-- Background Hijau Muda seperti di Homepage --}}
    <div id="Background" class="absolute top-0 w-full h-[150px] rounded-bl-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
    </div>

    {{-- TopNav (seperti di halaman login/register/dashboard) --}}
    <div id="TopNav" class="relative flex items-center justify-center px-5 mt-[60px] text-center w-full">
        <h2 class="font-bold text-[30px] leading-[45px] text-ngekos-black">
            Lupa Password?
        </h2>
    </div>

    <div class="flex items-center justify-center  py-12 px-5 mt-[170px] relative z-10">
        <div class="max-w-md w-full rounded-[30px] p-5 gap-4 bg-white shadow-[0px_12px_30px_0px_#0000000D] border border-[#F1F2F6] overflow-hidden">
            <div class="mb-4 text-sm text-ngekos-grey text-center"> {{-- Styling teks --}}
                {{ __('Lupa password Anda? Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password yang akan memungkinkan Anda memilih yang baru.') }}
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4"> {{-- flex-col gap-4 --}}
                @csrf

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Alamat Email</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis email Anda">
                    </label>
                    @error('email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-4"> {{-- flex justify-end for button --}}
                    <button type="submit" class="flex justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300">
                        {{ __('Kirim Tautan Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @include('includes.navigation')
@endsection