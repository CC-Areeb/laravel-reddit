<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedditPosts extends Model
{
    protected $fillable = [
        'title',
        'post',
        'community_id',
        'user_id',
        'up_votes',
        'down_votes',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
}
