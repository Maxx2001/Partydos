<?php

namespace Domain\Profile\Models;

use Database\Factories\GuestbookEntryFactory;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestbookEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): GuestbookEntryFactory
    {
        return GuestbookEntryFactory::new();
    }

    /** @return BelongsTo<User, $this> */
    public function profileUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }
}
