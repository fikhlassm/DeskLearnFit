<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'pengajar_id',
        'nama_kelas',
        'mata_pelajaran',
        'kode_kelas',
        'deskripsi',
        'kapasitas',
        'status',
        'theme_color',
        'cover_image',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
        ];
    }

    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }

    public function anggotaKelas(): HasMany
    {
        return $this->hasMany(AnggotaKelas::class);
    }

    /** Siswa yang terdaftar di kelas ini via pivot anggota_kelas. */
    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'anggota_kelas', 'kelas_id', 'siswa_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    public function topiks(): HasMany
    {
        return $this->hasMany(Topik::class);
    }
}
