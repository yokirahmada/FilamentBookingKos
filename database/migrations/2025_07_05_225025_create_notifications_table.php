<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_notifications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Menggunakan UUID sebagai primary key
            $table->string('type'); // Tipe notifikasi (misal: App\Notifications\InvoicePaid)
            // <<< UBAH BARIS INI >>>
            $table->uuidMorphs('notifiable'); // Menggunakan UUIDMorphs untuk relasi polimorfik berbasis UUID
            // Jika Anda tidak menggunakan UUID untuk user, gunakan:
            // $table->morphs('notifiable'); // Atau ini untuk ID integer biasa

            $table->json('data'); // Data notifikasi dalam format JSON
            $table->timestamp('read_at')->nullable(); // Kapan notifikasi dibaca
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};