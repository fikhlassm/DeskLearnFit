<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();
            $table->foreignId('siswa_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id']);
            $table->index('siswa_id');
            $table->index('kelas_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_kelas');
    }
};
