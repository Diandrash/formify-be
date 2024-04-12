<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowed_domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'domain',
    ];

    public function user() {
        return $this->belongsTo('user_id', User::class);
    }
}
