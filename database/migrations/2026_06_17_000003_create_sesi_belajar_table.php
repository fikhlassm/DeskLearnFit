<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_belajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('metode')->default('pomodoro');
            $table->string('judul')->nullable();
            $table->unsignedSmallInteger('durasi_fokus_menit')->default(25);
            $table->unsignedSmallInteger('durasi_istirahat_menit')->default(5);
            $table->unsignedSmallInteger('jumlah_siklus')->default(1);
            $table->enum('status', ['aktif', 'selesai', 'batal'])->default('aktif');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_belajar');
    }
};
