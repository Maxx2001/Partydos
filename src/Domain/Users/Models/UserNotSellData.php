<?php

namespace Domain\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\GuestUser
 *
 * @property int        $id
 * @property int        $user_id
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 */

class UserNotSellData extends Model
{
    protected $table = 'user_not_sell_data';

    protected $fillable = [
        'user_id',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
