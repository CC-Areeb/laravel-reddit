<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
