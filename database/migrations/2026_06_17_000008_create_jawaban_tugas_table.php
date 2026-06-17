<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')
                ->constrained('tugas')
                ->cascadeOnDelete();
            $table->foreignId('siswa_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->longText('jawaban_text')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedTinyInteger('nilai')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['terkirim', 'dinilai', 'terlambat'])->default('terkirim');
            $table->timestamps();

            $table->unique(['tugas_id', 'siswa_id']);
            $table->index(['siswa_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_tugas');
    }
};
