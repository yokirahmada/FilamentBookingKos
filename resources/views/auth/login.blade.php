{{-- resources/views/auth/login.blade.php --}}

@extends('layouts.app') {{-- Menggunakan layout app custom Anda --}}

@section('content')
    {{-- Background Hijau Muda seperti di Homepage --}}
    <div id="Background" class="absolute top-0 w-full h-[150px] rounded-bl-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
    </div>
    <div id="TopNav" class="relative flex items-center justify-center px-5 mt-[60px] text-center w-full"> {{-- justify-center untuk tengah horizontal --}}
        <h2 class="font-bold text-[30px] leading-[45px] text-ngekos-black">
            Login Akun Anda
        </h2>
    </div>


    <div class="flex items-center justify-center  py-12 px-5 mt-[170px] relative z-10">
        <div class="max-w-md w-full rounded-[30px] p-5 gap-4 bg-white shadow-[0px_12px_30px_0px_#0000000D] border border-[#F1F2F6] overflow-hidden">
            <div>
                <p class="text-sm text-ngekos-grey text-center">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-ngekos-orange hover:text-ngekos-black"> {{-- Menggunakan warna ngekos-orange --}}
                        Daftar akun baru!
                    </a>
                </p>
            </div>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form class="flex flex-col gap-4" method="POST" action="{{ route('login') }}"> {{-- Menggunakan route('login') --}}
                @csrf

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Alamat Email</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis email Anda">
                    </label>
                    @error('email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold">Password</p>
                    <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                        <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                            placeholder="Tulis password Anda">
                    </label>
                    @error('password')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-2"> {{-- Adjusted spacing and layout --}}
                    <label for="remember_me" class="flex items-center gap-[10px]"> {{-- Adjusted for custom gap --}}
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
                        <span class="text-sm text-ngekos-grey">{{ __('Remember me') }}</span> {{-- Using ngekos-grey --}}
                    </label>

                    @if (Route::has('password.request'))
                        <a class="font-medium text-ngekos-orange hover:text-ngekos-black text-sm underline" href="{{ route('password.request') }}"> {{-- Using ngekos-orange --}}
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @include('includes.navigation')
@endsection