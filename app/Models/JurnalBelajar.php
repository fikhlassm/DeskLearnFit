<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalBelajar extends Model
{
    use HasFactory;

    protected $table = 'jurnal_belajar';

    /** @var array<int, string> */
    protected $fillable = [
        'user_id',
        'tanggal',
        'judul',
        'isi_jurnal',
        'metode_yang_digunakan',
        'rating_efektivitas',
        'durasi_menit',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'rating_efektivitas' => 'integer',
            'durasi_menit' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
