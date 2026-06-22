<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashcardReview extends Model
{
    use HasFactory;

    protected $table = 'flashcard_reviews';

    /** @var array<int, string> */
    protected $fillable = [
        'flashcard_id',
        'sesi_id',
        'user_id',
        'benar',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'benar' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function flashcard(): BelongsTo
    {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiBelajar::class, 'sesi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
