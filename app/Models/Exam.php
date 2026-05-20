<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = ['title', 'description', 'duration_minutes'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
