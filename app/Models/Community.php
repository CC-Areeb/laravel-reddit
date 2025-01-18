<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
        ->withPivot('community_moderator');
    }

    public function community_users(): HasMany {
        return $this->hasMany(CommunityUsers::class);
    }

    public function pendingCommunityRequests(): HasMany {
        return $this->hasMany(PendingCommunityRequests::class, 'community_id');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');  // Customize the format here
    }
}
