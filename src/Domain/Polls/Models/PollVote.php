<?php

namespace Domain\Polls\Models;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'poll_option_id',
        'user_id',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\PollVoteFactory::new();
    }

    /** @phpstan-ignore-next-line */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /** @phpstan-ignore-next-line */
    public function option(): BelongsTo
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }

    /** @phpstan-ignore-next-line */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
