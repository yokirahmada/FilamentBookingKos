<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_is_approved_to_testimonials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Tambahkan kolom is_approved (boolean, default false)
            $table->boolean('is_approved')->default(false)->after('rating'); // Tambahkan setelah kolom 'rating'
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};