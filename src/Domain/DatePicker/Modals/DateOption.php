<?php

namespace Domain\DatePicker\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Domain\Events\Models\DateOption
 *
 * @property int $id
 * @property int $date_picker_id
 * @property string $date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $comment
 */
class DateOption extends Model
{


    protected $fillable = [
        'date_picker_id',
        'date',
        'start_time',
        'end_time',
        'comment',
    ];

    public function datePicker(): BelongsTo
    {
        return $this->belongsTo(DatePicker::class);
    }
}
