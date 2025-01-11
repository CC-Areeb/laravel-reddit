<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PendingCommunityRequests extends Model
{
    protected $fillable = [
        'community_id',
        'user_id',
        'accepted',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');  // Customize the format here
    }
}
