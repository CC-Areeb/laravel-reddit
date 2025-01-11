<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityUsers extends Model
{
    protected $fillable = [
        'community_id',
        'user_id',
        'community_moderator',
    ];
}
