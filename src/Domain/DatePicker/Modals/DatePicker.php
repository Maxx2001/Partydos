<?php

namespace Domain\DatePicker\Modals;

use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain\Events\Models\DatePicker
 *
 * @property int $id
 * @property string|null $unique_identifier
 * @property int $event_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property string|null $location
 */
class DatePicker extends Model
{
    protected $fillable = [
        'unique_identifier',
        'user_id',
        'title',
        'description',
        'location',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(DateOption::class);
    }
}
