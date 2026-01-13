<?php

namespace Domain\Polls\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'text',
        'created_by_user_id',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\PollOptionFactory::new();
    }

    /** @phpstan-ignore-next-line */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /** @phpstan-ignore-next-line */
    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    /** @phpstan-ignore-next-line */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
