<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();
            $table->foreignId('pengajar_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->timestamp('deadline')->nullable();
            $table->string('lampiran_path')->nullable();
            $table->enum('status', ['draf', 'terbit', 'ditutup'])->default('draf');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['kelas_id', 'status']);
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
