<?php

namespace Domain\Events\Models;

use Carbon\Carbon;
use Domain\Addresses\Models\Address;
use Domain\Events\Services\EventShareLinkService;
use Domain\Events\Services\GoogleCalendarLinkService;
use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
 * @property string $start_date_time
 * @property string $end_date_time
 * @property string|null $canceled_at
 */
class Event extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected static function newFactory()
    {
        return \Database\Factories\EventFactory::new();
    }

    protected $fillable = [
        'unique_identifier',
        'user_id',
        'guest_user_id',
        'title',
        'description',
        'start_date_time',
        'end_date_time',
        'canceled_at',
    ];

    /** @phpstan-ignore-next-line */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @phpstan-ignore-next-line */
    public function guestUser(): BelongsTo
    {
        return $this->belongsTo(GuestUser::class, 'guest_user_id');
    }

    /** @phpstan-ignore-next-line */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /** @phpstan-ignore-next-line */
    public function guestUsers(): BelongsToMany
    {
        return $this->belongsToMany(GuestUser::class)
            ->withTimestamps();
    }

    /** @phpstan-ignore-next-line */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function getShareLinkAttribute(): string
    {
        return (new EventShareLinkService())->generateShareLink($this);
    }

    public function getEventOwnerAttribute(): User|GuestUser|null
    {
        $user = $this->user;
        $guestUser = $this->guestUser;
        
        if ($user instanceof User) {
            return $user;
        }
        
        if ($guestUser instanceof GuestUser) {
            return $guestUser;
        }
        
        return null;
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
            $this->address?->address
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User|GuestUser>
     */
    public function getInvitedUsersAttribute()
    {
        $this->load('guestUsers', 'users');
        
        /** @var \Illuminate\Database\Eloquent\Collection<int, User|GuestUser> $invitedUsers */
        $invitedUsers = new \Illuminate\Database\Eloquent\Collection();
        
        foreach ($this->guestUsers as $guestUser) {
            if ($guestUser instanceof GuestUser) {
                $invitedUsers->push($guestUser);
            }
        }
        
        foreach ($this->users as $user) {
            if ($user instanceof User) {
                $invitedUsers->push($user);
            }
        }
        
        return $invitedUsers;
    }

    /** @param Builder<Event> $query */
    public function scopeFutureEvents(Builder $query): void
    {
        $query->where('start_date_time', '>=', now())
            ->orWhere(function (Builder $query) {
                $query->whereNotNull('end_date_time')
                    ->where('end_date_time', '>=', now());
            });
    }

    /** @param Builder<Event> $query */
    public function scopeHistoryEvents(Builder $query): void
    {
        $query->where('start_date_time', '<', now())
            ->orWhere(function (Builder $query) {
                $query->whereNotNull('end_date_time')
                    ->where('end_date_time', '<', now());
            });
    }

    public function registerMediaConversions(?Media $media = null): void
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
