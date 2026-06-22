<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntriNotebook extends Model
{
    use HasFactory;

    protected $table = 'entri_notebook';

    public const TIPE_BLURTING = 'blurting';

    public const TIPE_FEYNMAN = 'feynman';

    /** @var array<int, string> */
    protected $fillable = [
        'sesi_id',
        'tipe',
        'konten',
        'analisis_sistem',
        'skor_keyakinan',
        'kata_kunci_cocok',
    ];

    protected function casts(): array
    {
        return [
            'skor_keyakinan' => 'integer',
            'kata_kunci_cocok' => 'array',
        ];
    }

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiBelajar::class, 'sesi_id');
    }

    public function isBlurting(): bool
    {
        return $this->tipe === self::TIPE_BLURTING;
    }

    public function isFeynman(): bool
    {
        return $this->tipe === self::TIPE_FEYNMAN;
    }
}
