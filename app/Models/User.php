<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'quiz_result',
        'quiz_scores',
        'bio',
        'tujuan_belajar',
        'jenjang',
        'no_hp',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'quiz_scores' => 'array',
        ];
    }

    public function jurnalBelajar(): HasMany
    {
        return $this->hasMany(JurnalBelajar::class, 'user_id');
    }

    public function sesiBelajar(): HasMany
    {
        return $this->hasMany(SesiBelajar::class, 'user_id');
    }

    public function kelasDiajar(): HasMany
    {
        return $this->hasMany(Kelas::class, 'pengajar_id');
    }

    public function anggotaKelas(): HasMany
    {
        return $this->hasMany(AnggotaKelas::class, 'siswa_id');
    }

    public function kelasDiikuti(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'anggota_kelas', 'siswa_id', 'kelas_id')
            ->withPivot('joined_at')
            ->withTimestamps();
    }

    public function tugasDibuat(): HasMany
    {
        return $this->hasMany(Tugas::class, 'pengajar_id');
    }

    public function jawabanTugas(): HasMany
    {
        return $this->hasMany(JawabanTugas::class, 'siswa_id');
    }

    public function materiDibuat(): HasMany
    {
        return $this->hasMany(Materi::class, 'pengajar_id');
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isPengajar(): bool
    {
        return $this->role === 'pengajar';
    }
}
