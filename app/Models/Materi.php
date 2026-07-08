<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'kelas_id',
        'topik_id',
        'pengajar_id',
        'judul',
        'deskripsi',
        'konten',
        'tipe',
        'link_url',
        'file_path',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }

    public function topik(): BelongsTo
    {
        return $this->belongsTo(Topik::class);
    }

    public function isTerbit(): bool
    {
        return $this->status === 'terbit';
    }
}
