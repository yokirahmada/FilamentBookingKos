{{-- resources/views/pages/notifications/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div id="Background" class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]"></div>
    <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
        <a href="{{ route('dashboard') }}" class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
            <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
        </a>
        <p class="font-semibold text-lg">Notifikasi Anda</p>
        <div class="dummy-btn w-12"></div>
    </div>

    <section class="flex flex-col gap-4 px-5 mt-[170px]">
        @forelse ($notifications as $notification)
            <div class="flex items-start gap-4 p-4 bg-white rounded-lg shadow-sm border {{ $notification->read_at ? 'border-gray-200' : 'border-indigo-500' }}">
                {{-- <<< UBAH BARIS INI >>> --}}
                 <img src="{{ asset('assets/images/icons/notification.svg') }}" class="w-[28px] h-[28px]" alt="Notification Icon">
                <div class="flex flex-col flex-grow">
                    <h3 class="font-semibold text-base leading-tight">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] ?? 'Tidak ada detail pesan.' }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    @if(isset($notification->data['action_url']))
                        <a href="{{ $notification->data['action_url'] }}" class="text-indigo-600 hover:text-indigo-500 text-sm mt-2">Lihat Detail</a>
                    @endif
                </div>
                @unless($notification->read_at)
                    <span class="w-2 h-2 rounded-full bg-indigo-600 flex-shrink-0 mt-1" title="Belum Dibaca"></span>
                @endunless
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                <p class="text-ngekos-grey">Anda belum memiliki notifikasi.</p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </section>

    @include('includes.navigation')
@endsection