<?php

namespace App\Models;

use Domain\Events\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventIntegration extends Model
{
    protected $fillable = [
        'event_id',
        'provider',
        'external_calendar_id',
        'external_event_id',
        'sync_enabled',
        'last_synced_at',
        'last_sync_hash',
    ];

    protected function casts(): array
    {
        return [
            'sync_enabled' => 'boolean',
            'last_synced_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Event, $this> */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
