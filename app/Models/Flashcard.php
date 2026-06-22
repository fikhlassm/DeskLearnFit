<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flashcard extends Model
{
    use HasFactory;

    protected $table = 'flashcards';

    /** @var array<int, string> */
    protected $fillable = [
        'sesi_id',
        'pertanyaan',
        'jawaban',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiBelajar::class, 'sesi_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(FlashcardReview::class, 'flashcard_id');
    }
}
