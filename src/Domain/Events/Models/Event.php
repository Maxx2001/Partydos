<?php

namespace Domain\Events\Models;

use Carbon\Carbon;
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

    public function getEventOwnerAttribute(): User|GuestUser
    {
        return $this->user ?? $this->guestUser;
    }

    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->start_date_time)->format('D d F');
    }

    public function getFormattedTimeAttribute(): string
    {
        $time = Carbon::parse($this->start_date_time)->format('H:i');
        if ($this->end_date_time) {
            $time .= ' - ' . Carbon::parse($this->end_date_time)->format('H:i');
        }

        return $time;
    }

    public function getIsoStartDateTimeAttribute(): string
    {
        return Carbon::parse($this->start_date_time)->toISOString();
    }

    public function getIsoEndDateTimeAttribute(): ?string
    {
        return $this->end_date_time ? Carbon::parse($this->end_date_time)->toISOString() : null;
    }
}
