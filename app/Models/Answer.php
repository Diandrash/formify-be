<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_id',
        'question_id',
        'value',
    ];

    public function answer() {
        return $this->belongsTo(Answer::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
