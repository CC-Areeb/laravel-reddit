<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');  // Customize the format here
    }
}
