{{-- resources/views/profile/edit.blade.php --}}

@extends('layouts.app') {{-- Menggunakan layout app custom Anda --}}

@section('content')
    {{-- Background Hijau Muda seperti di Homepage --}}
    <div id="Background" class="absolute top-0 w-full h-[280px] rounded-bl-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
    </div>

    {{-- TopNav (seperti di halaman login/register/dashboard) --}}
    <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
        <a href="{{ route('dashboard') }}" class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
            <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="Back Icon">
        </a>
        <p class="font-semibold text-lg text-ngekos-black">Pengaturan Profil</p> {{-- Judul halaman profil --}}
        <div class="dummy-btn w-12"></div> {{-- Dummy untuk menjaga justify-between --}}
    </div>

    {{-- Main Content Area --}}
    <div class="relative z-10 py-12 px-5 mt-[30px] flex items-center justify-center"> {{-- Margin-top disesuaikan --}}
         <div class="max-w-md w-full flex flex-col gap-6"> {{-- Container untuk cards profil, gunakan flex-col dan gap --}}

            {{-- Update Profile Information Form --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-[0px_12px_30px_0px_#0000000D] rounded-[30px] border border-[#F1F2F6]"> {{-- Styling card Anda --}}
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password Form --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-[0px_12px_30px_0px_#0000000D] rounded-[30px] border border-[#F1F2F6]"> {{-- Styling card Anda --}}
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

        </div>
    </div>

    @include('includes.navigation')
@endsection