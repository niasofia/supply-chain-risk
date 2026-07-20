<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk menambahkan kolom 'role'.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' dengan default 'user' tepat di bawah kolom 'email'
            $table->string('role')->default('user')->after('email');
        });
    }

    /**
     * Batalkan migration (rollback) untuk menghapus kolom 'role'.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom 'role' jika migration dibatalkan/di-rollback
            $table->dropColumn('role');
        });
    }
};