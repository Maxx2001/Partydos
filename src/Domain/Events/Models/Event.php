<?php

namespace Domain\Events\Models;

use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * App\Models\Event
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

    protected $appends = ['share_link'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($event) {
            $event->unique_identifier = Str::random();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guestUser(): belongsTo
    {
        return $this->belongsTo(GuestUser::class, 'guest_user_id');
    }

    public function guestUsers(): belongsToMany
    {
        return $this->belongsToMany(GuestUser::class)
            ->withTimestamps();
    }

    public function getEventOwnerRelation(): string
    {
        if ($this->user_id) {
            return 'user';
        }

        return 'guestUser';
    }

    public function getShareLinkAttribute(): string
    {
        return config('app.url') . '/event-invite/' . $this->unique_identifier;
    }
}
