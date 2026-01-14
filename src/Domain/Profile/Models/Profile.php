<?php

namespace Domain\Profile\Models;

use Database\Factories\ProfileFactory;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'birthdate' => 'date',
        'interests' => 'array',
        'favorite_music' => 'array',
        'party_style_tags' => 'array',
    ];

    protected static function newFactory(): ProfileFactory
    {
        return ProfileFactory::new();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<GuestbookEntry, $this> */
    public function guestbookEntries(): HasMany
    {
        return $this->hasMany(GuestbookEntry::class, 'profile_user_id', 'user_id');
    }
}
