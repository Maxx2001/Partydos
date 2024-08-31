<?php

namespace App\Models;

use App\Traits\HasEvents;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\GuestUser
 *
 * @property int        $id
 * @property string     $name
 * @property string     $email
 * @property Collection $ownedEvents
 * @property Collection $events
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 */
class GuestUser extends Model
{
    use HasFactory;
    use HasEvents;

    protected $guarded = [];
}
