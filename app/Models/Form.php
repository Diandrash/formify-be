<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'creator_id',
        'name',
        'slug',
        'description',
        'limit_one_response',
    ];

    public function getRouteKeyName(){
        return 'slug';
    }

    public function allowedDomains() {
        return $this->hasMany(Allowed_domain::class);
    }
    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function creator() {
        return $this->belongsTo('creator_id', User::class);
    }
}
