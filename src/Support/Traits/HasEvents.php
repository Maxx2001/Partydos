<?php

namespace Support\Traits;

use Domain\Events\Models\Event;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasEvents
{
    public function ownedEvents(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->withTimestamps();
    }
}
