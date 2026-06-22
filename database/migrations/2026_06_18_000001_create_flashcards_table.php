<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')
                ->constrained('sesi_belajar')
                ->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->text('jawaban');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['sesi_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flashcards');
    }
};
