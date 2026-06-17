<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();
            $table->foreignId('pengajar_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable();
            $table->enum('tipe', ['teks', 'link', 'file'])->default('teks');
            $table->string('link_url')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['draf', 'terbit'])->default('draf');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['kelas_id', 'status']);
            $table->index('pengajar_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
