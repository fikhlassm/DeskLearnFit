<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_belajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('judul')->nullable();
            $table->text('isi_jurnal');
            $table->string('metode_yang_digunakan')->nullable();
            $table->unsignedTinyInteger('rating_efektivitas')->nullable();
            $table->unsignedSmallInteger('durasi_menit')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_belajar');
    }
};
