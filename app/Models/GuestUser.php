<?php

namespace App\Models;

use App\Traits\HasEvents;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\GuestUser
 *
 * @property int    $id
 * @property string $name
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class GuestUser extends Model
{
    use HasFactory;
    use HasEvents;

    protected $guarded = [];
}
