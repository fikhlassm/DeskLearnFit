<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'kelas_id',
        'pengajar_id',
        'judul',
        'deskripsi',
        'deadline',
        'lampiran_path',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
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

    public function jawabanTugas(): HasMany
    {
        return $this->hasMany(JawabanTugas::class);
    }

    public function isTerbit(): bool
    {
        return $this->status === 'terbit';
    }

    public function isBerlaku(): bool
    {
        return $this->status === 'terbit'
            && ($this->deadline === null || $this->deadline->isFuture());
    }
}
