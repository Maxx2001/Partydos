<?php

namespace Domain\Events\Models;

use Domain\GuestUsers\Models\GuestUser;
use Domain\Users\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventDateOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'start_date_time',
        'end_date_time',
    ];

    protected $appends = [
        'formatted_time',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_date_option_user')
            ->withTimestamps();
    }

    public function guestUsers(): BelongsToMany
    {
        return $this->belongsToMany(GuestUser::class, 'event_date_option_guest_user')
            ->withTimestamps();
    }

    public function getFormattedTimeAttribute(): string
    {
        $start = \Carbon\Carbon::parse($this->start_date_time)->format('H:i');
        $end = $this->end_date_time ? \Carbon\Carbon::parse($this->end_date_time)->format('H:i') : null;
        return $end && $end !== $start ? $start.' - '.$end : $start;
    }
}
