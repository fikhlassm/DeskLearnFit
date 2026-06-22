<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entri_notebook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')
                ->constrained('sesi_belajar')
                ->cascadeOnDelete();
            $table->enum('tipe', ['blurting', 'feynman']);
            $table->text('konten');
            $table->text('analisis_sistem')->nullable();
            $table->unsignedSmallInteger('skor_keyakinan')->nullable();
            $table->json('kata_kunci_cocok')->nullable();
            $table->timestamps();

            $table->index(['sesi_id', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entri_notebook');
    }
};
