<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flashcard_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flashcard_id')
                ->constrained('flashcards')
                ->cascadeOnDelete();
            $table->foreignId('sesi_id')
                ->constrained('sesi_belajar')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->boolean('benar');
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();

            $table->index(['sesi_id', 'user_id']);
            $table->index(['flashcard_id', 'benar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flashcard_reviews');
    }
};
