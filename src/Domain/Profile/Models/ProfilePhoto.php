<?php

namespace Domain\Profile\Models;

use Database\Factories\ProfilePhotoFactory;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilePhoto extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): ProfilePhotoFactory
    {
        return ProfilePhotoFactory::new();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
