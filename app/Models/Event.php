<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Event
 *
 * @property int    $id
 * @property int    $user_id
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
}
