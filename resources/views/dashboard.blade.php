{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app') {{-- Menggunakan layout app custom Anda --}}

@section('content')
    {{-- Background Hijau Muda dari Homepage --}}
    <div id="Background" class="absolute top-0 w-full h-[150px] rounded-bl-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
    </div>

    {{-- TopNav dari Homepage --}}
    <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[40px]">
        <div class="flex flex-col gap-1">
            <p class="text-sm">Selamat
                {{-- Logic waktu --}}
                @php
                    $hour = date('H'); // Menggunakan zona waktu dari config/app.php
                    $greeting = '';
                    if ($hour >= 3 && $hour < 11) {
                        $greeting = 'pagi';
                    } elseif ($hour >= 11 && $hour < 16) {
                        $greeting = 'siang';
                    } elseif ($hour >= 16 && $hour < 19) {
                        $greeting = 'sore';
                    } else {
                        $greeting = 'malam';
                    }
                @endphp
                {{ $greeting }}@auth, {{ Auth::user()->name }}@endauth
            </p>
            <h1 class="font-bold text-xl leading-[30px]">Dashboard Anda</h1>
        </div>

        <div class="flex items-center gap-[10px]">
            @auth {{-- Tampilkan ini jika user SUDAH LOGIN --}}
                {{-- Icon Notifikasi --}}
                <a href="{{ route('notifications.index') }}" {{-- <<< LINK KE HALAMAN NOTIFIKASI BARU >>> --}}
                    class="relative w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
                    <img src="{{ asset('assets/images/icons/notification.svg') }}" class="w-[28px] h-[28px]" alt="Notification Icon">
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                {{-- Icon Setting --}}
                <a href="{{ route('profile.edit') }}"
                    class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
                    <img src="{{ asset('assets/images/icons/setting.svg') }}" class="w-[28px] h-[28px]" alt="Setting Icon">
                </a>
                {{-- Icon Logout (gunakan form POST untuk logout) --}}
                <form method="POST" action="{{ route('logout') }}" class="inline-block"> {{-- Menggunakan route('akun.logout') --}}
                    @csrf
                    <button type="submit" class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white p-0 border-0">
                        <img src="{{ asset('assets/images/icons/logout.svg') }}" class="w-[28px] h-[28px]" alt="Logout Icon">
                    </button>
                </form>
            @endauth

            @guest {{-- Tampilkan ini jika user BELUM LOGIN --}}
                {{-- Icon Login --}}
                <a href="{{ route('login') }}" {{-- Menggunakan route('akun.login') --}}
                    class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
                    <img src="{{ asset('assets/images/icons/login.svg') }}" class="w-[28px] h-[28px]" alt="Login Icon">
                </a>
            @endguest
        </div>
    </div>

    {{-- Bagian Riwayat Transaksi --}}
    <section id="History" class="flex flex-col gap-4 px-5 mt-[100px]">
        <div class="flex items-center justify-center">
            <h2 class="font-bold text-lg">Riwayat Transaksi Anda</h2>
        </div>
        <div class="flex flex-col gap-4">
            @forelse ($transactions as $transaction)
                {{-- LINK RIWAYAT TRANSAKSI YANG BENAR (GET) --}}
                <a href="{{ route('show-booking-details-by-code', ['code' => $transaction->code]) }}" class="card">
                    <div class="flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#91BF77] transition-all duration-300">
                        {{-- Thumbnail Kos --}}
                        <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                            <img src="{{ asset('storage/'. $transaction->boardingHouse->thumbnail) }}" class="w-full h-full object-cover" alt="Kos Thumbnail">
                        </div>
                        <div class="flex flex-col gap-2 w-full">
                            <h3 class="font-semibold text-lg leading-[24px] line-clamp-1">{{ $transaction->boardingHouse->name }}</h3>
                            <p class="text-sm text-ngekos-grey">{{ $transaction->room->name }}</p>
                            <div class="flex items-center gap-[6px]">
                                <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-4 h-4 flex shrink-0" alt="icon">
                                <p class="text-xs text-ngekos-grey">{{ $transaction->boardingHouse->city->name }}</p>
                            </div>
                            <div class="flex items-center gap-[6px]">
                                <img src="{{ asset('assets/images/icons/calendar.svg') }}" class="w-4 h-4 flex shrink-0" alt="icon">
                                <p class="text-xs text-ngekos-grey">{{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMM YYYY') }} ({{ $transaction->duration }} bln)</p> {{-- Format tahun agar lebih jelas --}}
                            </div>
                            <hr class="border-[#F1F2F6] my-1">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-base text-ngekos-orange">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                @if($transaction->payment_status === 'pending')
                                    <span class="rounded-full py-1 px-3 text-xs font-bold bg-yellow-100 text-yellow-800">Tertunda</span>
                                @elseif($transaction->payment_status === 'paid' || $transaction->payment_status === 'success')
                                    <span class="rounded-full py-1 px-3 text-xs font-bold bg-green-100 text-green-800">Sukses</span>
                                @else
                                    <span class="rounded-full py-1 px-3 text-xs font-bold bg-gray-100 text-gray-800">{{ ucfirst($transaction->payment_status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-10 bg-white rounded-[30px] shadow-sm">
                    <p class="text-ngekos-grey">Anda belum memiliki transaksi. <a href="{{ route('home') }}" class="text-ngekos-orange hover:text-ngekos-black">Cari kos sekarang!</a></p>
                </div>
            @endforelse
        </div>
    </section>

    @include('includes.navigation')
@endsection