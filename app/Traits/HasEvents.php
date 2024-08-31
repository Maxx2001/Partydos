<?php

namespace App\Traits;

use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
