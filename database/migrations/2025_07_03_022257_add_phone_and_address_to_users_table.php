<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom phone_number setelah email
            $table->string('phone_number')->nullable()->after('email');
            // Tambahkan kolom address setelah phone_number
            $table->text('address')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom phone_number dan address jika migrasi di-rollback
            $table->dropColumn('phone_number');
            $table->dropColumn('address');
        });
    }
};
