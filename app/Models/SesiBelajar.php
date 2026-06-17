<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SesiBelajar extends Model
{
    use HasFactory;
    protected $table = 'sesi_belajar';

    /** @var array<int, string> */
    protected $fillable = [
        'user_id',
        'metode',
        'judul',
        'durasi_fokus_menit',
        'durasi_istirahat_menit',
        'jumlah_siklus',
        'status',
        'started_at',
        'completed_at',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'durasi_fokus_menit'     => 'integer',
            'durasi_istirahat_menit' => 'integer',
            'jumlah_siklus'          => 'integer',
            'started_at'             => 'datetime',
            'completed_at'           => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }
}
