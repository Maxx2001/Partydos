<?php

namespace Domain\Polls\Models;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'question',
        'description',
        'status',
        'vote_mode',
        'guests_can_add_options',
        'created_by_user_id',
    ];

    protected $casts = [
        'guests_can_add_options' => 'boolean',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\PollFactory::new();
    }

    /** @phpstan-ignore-next-line */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /** @phpstan-ignore-next-line */
    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class);
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
