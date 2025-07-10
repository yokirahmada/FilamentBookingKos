## Aplikasi Web Sewa Kamar Kos 

Aplikasi web modern yang dirancang untuk mempermudah proses pencarian dan penyewaan kamar kos. Dibangun dengan fokus pada pengalaman pengguna yang intuitif dan manajemen backend yang efisien, aplikasi ini menghadirkan solusi lengkap bagi pemilik kos dan pencari kamar.

## Fitur Utama
Pencarian Kos Interaktif: Jelajahi dan cari kamar kos berdasarkan berbagai kriteria seperti nama kosan, kategori (misalnya kos putra, putri, campur), tingkat popularitas, dan lokasi kota.

Sistem Pemesanan Kamar Cerdas: Pengguna dapat dengan bebas memilih kamar kos yang tersedia. Sistem ini dilengkapi dengan penanganan race condition pembayaran, memastikan bahwa kamar hanya dialokasikan kepada pembayar tercepat, dengan otomatisasi status ketersediaan.

Opsi Pembayaran Fleksibel: Menawarkan dua metode pembayaran yang nyaman melalui integrasi Midtrans Payment Gateway: pembayaran dimuka 30% atau pembayaran lunas 100%.

Riwayat Transaksi Komprehensif: Halaman khusus untuk mencari dan melihat riwayat pembayaran berdasarkan ID Booking, email, dan nomor telepon pelanggan, memberikan transparansi penuh.

Saluran Komunikasi Pengguna: Pengguna dapat dengan mudah mengirimkan aduan, saran, dan kritik langsung ke developer melalui formulir di aplikasi.

Panel Admin Filament yang Kuat: Fitur panel admin yang lengkap menggunakan FilamentPHP, memungkinkan manajemen yang efisien terhadap data kos (termasuk galeri foto kamar), kamar, kategori, kota, pengguna, dan testimonial (dilengkapi dengan fitur approve dan unggah galeri foto testimonial).

Notifikasi Otomatis: Sistem pengiriman notifikasi yang canggih (melalui email dan notifikasi in-app) untuk konfirmasi pembayaran sukses (kepada pelanggan dan admin), serta notifikasi pembatalan otomatis untuk transaksi yang terlambat membayar.

## Detail Teknologi
Aplikasi ini dibangun di atas pondasi teknologi modern untuk kinerja, skalabilitas, dan kemudahan pengembangan:

Backend Framework: Laravel 11.31.0

Admin Panel: Laravel Filament 3.2

Frontend Interactivity: Livewire 3

Styling Utility-First: Tailwind CSS

Payment Gateway: Midtrans Sandbox API

Layanan Email: Mailtrap (untuk pengujian, dengan konfigurasi SMTP siap produksi)