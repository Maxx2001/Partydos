<?php

namespace App\Traits;

use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasEvents
{
    public function ownedEvents(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
