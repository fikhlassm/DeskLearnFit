<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $fillable = ['user_id', 'rating', 'komentar', 'is_tampil'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_tampil' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
