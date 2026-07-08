<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanTugas extends Model
{
    use HasFactory;

    protected $table = 'jawaban_tugas';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'tipe',
        'jawaban_text',
        'link_url',
        'file_path',
        'submitted_at',
        'nilai',
        'feedback',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'nilai' => 'integer',
        ];
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
