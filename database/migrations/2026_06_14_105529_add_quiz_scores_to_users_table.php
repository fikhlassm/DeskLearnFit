<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // quiz_result sudah ada, tambah quiz_scores jika belum ada
            if (!Schema::hasColumn('users', 'quiz_scores')) {
                $table->json('quiz_scores')->nullable()->after('quiz_result');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists('quiz_scores');
        });
    }
};