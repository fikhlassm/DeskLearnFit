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
        Schema::table('tugas', function (Blueprint $table) {
            $table->enum('tipe', ['teks', 'link', 'file'])->default('teks')->after('deskripsi');
            $table->string('link_url')->nullable()->after('tipe');
        });

        Schema::table('jawaban_tugas', function (Blueprint $table) {
            $table->enum('tipe', ['teks', 'link', 'file'])->default('teks')->after('siswa_id');
            $table->string('link_url')->nullable()->after('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_tugas', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'link_url']);
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'link_url']);
        });
    }
};
