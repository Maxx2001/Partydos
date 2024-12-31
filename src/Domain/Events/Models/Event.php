<?php

namespace Domain\Events\Models;

use Carbon\Carbon;
use Domain\Events\Services\EventShareLinkService;
use Domain\Events\Services\GoogleCalendarLinkService;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'unique_identifier',
        'user_id',
        'guest_user_id',
        'title',
        'description',
        'location',
        'start_date_time',
        'end_date_time',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guestUser(): BelongsTo
    {
        return $this->belongsTo(GuestUser::class, 'guest_user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
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
        if ($this->end_date_time && $this->end_date_time !== $this->start_date_time) {
            $time .= ' - ' . Carbon::parse($this->end_date_time)->format('H:i');
        }

        return $time;
    }

    public function getIsoStartDateTimeAttribute(): string
    {
        return Carbon::parse($this->start_date_time)->format('Y-m-d\TH:i:s');

    }

    public function getIsoEndDateTimeAttribute(): ?string
    {
        return $this->end_date_time
            ? Carbon::parse($this->end_date_time)->format('Y-m-d\TH:i:s')
            : null;
    }

    public function getGoogleCalendarLinkAttribute(): string
    {
        return GoogleCalendarLinkService::generateLink(
            $this->iso_start_date_time,
            $this->iso_end_date_time,
            $this->title,
            $this->description,
            $this->location
        );
    }

    public function getInvitedUsersAttribute()
    {
        $this->load('guestUsers', 'users');
        return $this->guestUsers->merge($this->users);
    }

    public function scopeFutureEvents(Builder $query): void
    {
        $query->where('start_date_time', '>=', now())
            ->orWhere(function (Builder $query) {
                $query->whereNotNull('end_date_time')
                    ->where('end_date_time', '>=', now());
            });
    }

    public function scopeHistoryEvents(Builder $query): void
    {
        $query->where('start_date_time', '<', now())
            ->orWhere(function (Builder $query) {
                $query->whereNotNull('end_date_time')
                    ->where('end_date_time', '<', now());
            });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(100)
            ->height(100);

        $this->addMediaConversion('small')
            ->width(400)
            ->height(300);

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(600);

        $this->addMediaConversion('large')
            ->width(1600)
            ->height(1200);
    }
}
