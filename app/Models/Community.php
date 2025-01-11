<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = [
        'name',
        'description',
        'banner',
        'rules',
        'theme',
        'type',
        'creator_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
