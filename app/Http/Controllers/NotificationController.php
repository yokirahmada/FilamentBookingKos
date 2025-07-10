<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(10); // Ambil notifikasi terbaru, dengan pagination

        // Tandai semua notifikasi yang saat ini ditampilkan sebagai sudah dibaca
        // $user->unreadNotifications->markAsRead(); // Ini akan tandai semua notif belum dibaca sebagai sudah dibaca
        // Atau jika hanya ingin yang ditampilkan saat ini:
        // $user->unreadNotifications->whereIn('id', $notifications->pluck('id'))->markAsRead();

        return view('pages.notifications.index', compact('notifications'));
    }

    // Metode untuk menandai satu notifikasi sebagai sudah dibaca (opsional)
    public function markAsRead($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()->markAsRead();
        return redirect()->back();
    }

    // Metode untuk menandai semua notifikasi sebagai sudah dibaca (opsional)
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
}