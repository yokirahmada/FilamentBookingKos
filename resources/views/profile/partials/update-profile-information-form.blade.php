{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}

<section>
    <header>
        <h2 class="font-bold text-xl leading-[30px] text-ngekos-black"> {{-- Styling judul Anda --}}
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-ngekos-grey"> {{-- Styling teks ngekos-grey --}}
            {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 flex flex-col gap-4"> {{-- flex-col gap-4 --}}
        @csrf
        @method('patch')

        <div class="flex flex-col gap-2 mt-[10px]"> {{-- Styling input field --}}
            <p class="font-semibold">Nama Lengkap</p>
            <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="Nama Lengkap Anda">
            </label>
            @error('name')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <p class="font-semibold">Alamat Email</p>
            <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input id="email" name="email" type="email" value="{{ old('email', $user->email)}}" required autocomplete="username"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="email@example.com">
            </label>
            @error('email')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-ngekos-grey"> {{-- Styling teks --}}
                    {{ __('Alamat email Anda belum terverifikasi.') }}
                    <button form="send-verification" class="underline text-ngekos-orange hover:text-ngekos-black rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ngekos-orange"> {{-- Styling link --}}
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-2">
            <p class="font-semibold">Nomor Telepon</p>
            <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/call.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="Tulis nomor telepon Anda">
            </label>
            @error('phone_number')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <p class="font-semibold">Alamat Lengkap</p>
            <label class="flex items-center w-full rounded-[22px] p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <textarea name="address" id="address"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="Jalan contoh No. 123, Kota Anda">{{ old('address', $user->address) }}</textarea>
            </label>
            @error('address')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>


        <div class="flex items-center justify-end mt-4"> {{-- Flex untuk tombol Save --}}
            <button type="submit" class="flex justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-ngekos-grey ms-4" {{-- Menggunakan ngekos-grey dan ms-4 --}}
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>