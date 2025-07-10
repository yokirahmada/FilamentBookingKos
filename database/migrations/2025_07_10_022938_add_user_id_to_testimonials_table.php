<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Tambahkan user_id sebagai foreign key
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->after('id');
        
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Hapus foreign key
            $table->dropColumn('user_id');
            // Kembalikan kolom 'photo' dan 'name' jika di-rollback (ini tetap ada di down())
            $table->string('photo')->nullable();
            $table->string('name');
        });
    }
};