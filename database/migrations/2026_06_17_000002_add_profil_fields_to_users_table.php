<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('quiz_scores');
            }
            if (! Schema::hasColumn('users', 'tujuan_belajar')) {
                $table->string('tujuan_belajar')->nullable()->after('bio');
            }
            if (! Schema::hasColumn('users', 'jenjang')) {
                $table->string('jenjang')->nullable()->after('tujuan_belajar');
            }
            if (! Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('jenjang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter(
                ['bio', 'tujuan_belajar', 'jenjang', 'no_hp'],
                fn (string $col) => Schema::hasColumn('users', $col)
            ));
        });
    }
};
