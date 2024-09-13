<?php

namespace Domain\Events\Models;

use Domain\Events\Services\EventShareLinkService;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Domain\Events\Models\Event
 *
 * @property int $id
 * @property string $unique_identifier
 * @property int $user_id
 * @property int $guest_user_id
 * @property string $title
 * @property string $description
 * @property string $location
 * @property string $start_date_time
 * @property string $end_date_time
 */
class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guestUser(): BelongsTo
    {
        return $this->belongsTo(GuestUser::class, 'guest_user_id');
    }

    public function guestUsers(): BelongsToMany
    {
        return $this->belongsToMany(GuestUser::class)
            ->withTimestamps();
    }

    public function getShareLinkAttribute(): string
    {
        return (new EventShareLinkService())->generateShareLink($this);
    }
}
