<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingCommunityRequests extends Model
{
    protected $fillable = [
        'community_id',
        'user_id',
        'accepted',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function community(): BelongsTo {
        return $this->belongsTo(Community::class, 'community_id');
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');  // Customize the format here
    }
}
