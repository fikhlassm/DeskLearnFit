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
        Schema::table('materi', function (Blueprint $table) {
            $table->foreignId('topik_id')->nullable()->after('kelas_id')->constrained('topiks')->nullOnDelete();
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->foreignId('topik_id')->nullable()->after('kelas_id')->constrained('topiks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materi_and_tugas', function (Blueprint $table) {
            //
        });
    }
};
