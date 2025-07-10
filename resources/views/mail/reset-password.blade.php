{{-- resources/views/mail/reset-password.blade.php --}}

@extends('vendor.mail.html.layout') {{-- Mengextends layout default yang baru dimodifikasi --}}

@section('header-title', 'Reset Password Anda') {{-- Judul untuk header email --}}

@section('content')
    <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Halo,</p>
    <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>

    <p style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
        <a href="{{ $url }}" class="button" style="display: inline-block; padding: 12px 25px; background-color: #FF5500; color: #ffffff; text-decoration: none; border-radius: 50px; font-weight: bold;">Reset Password</a>
    </p>

    <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Tautan reset password ini akan kedaluwarsa dalam {{ $count }} menit.</p>
    <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Jika Anda tidak meminta reset password, tidak ada tindakan lebih lanjut yang diperlukan.</p>

    <small style="display: block; margin-top: 20px; font-size: 12px; color: #777777;">Jika Anda mengalami masalah saat mengklik tombol "Reset Password", salin dan tempel URL di bawah ini ke browser web Anda: <a href="{{ $url }}" style="color: #FF5500; word-break: break-all;">{{ $url }}</a></small>

    <p style="margin-top: 20px; margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Salam hangat,</p>
    <p style="margin-bottom: 15px; line-height: 1.6; font-size: 16px;">Tim Istana Graha Kos</p>
@endsection