{{-- resources/views/profile/partials/update-password-form.blade.php --}}

<section>
    <header>
        <h2 class="font-bold text-xl leading-[30px] text-ngekos-black">
            {{ __('Perbarui Password') }}
        </h2>
        <p class="mt-1 text-sm text-ngekos-grey">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 flex flex-col gap-4"> {{-- flex-col gap-4 --}}
        @csrf
        @method('put')

        <div class="flex flex-col gap-2 mt-[10px]">
            <p class="font-semibold">Password Saat Ini</p>
            <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="Password saat ini">
            </label>
            @error('current_password')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <p class="font-semibold">Password Baru</p>
            <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="Password baru">
            </label>
            @error('password')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <p class="font-semibold">Konfirmasi Password Baru</p>
            <label class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white border border-[#F1F2F6] focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300">
                <img src="{{ asset('assets/images/icons/password.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="appearance-none outline-none w-full font-semibold placeholder:text-ngekos-grey placeholder:font-normal"
                    placeholder="Konfirmasi password baru">
            </label>
            @error('password_confirmation')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="flex justify-center rounded-full p-[14px_20px] bg-ngekos-orange font-bold text-white hover:bg-ngekos-black transition-all duration-300">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-ngekos-grey ms-4"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>