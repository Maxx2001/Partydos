<?php

namespace Domain\Users\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Support\Traits\HasEvents;


/**
 * App\Models\User
 *
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property Carbon|null $email_verified_at
 * @property string      $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $profile_photo_path
 * @property Collection  $ownedEvents
 * @property Collection  $events
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasEvents;
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function upcomingEvents(): Collection
    {
        $invitedEvents = $this->events()->with('address')->futureEvents()->orderBy('start_date_time')->get();
        $ownedEvents = $this->ownedEvents()->with('address')->futureEvents()->orderBy('start_date_time')->get();

        return $invitedEvents->merge($ownedEvents);
    }

    public function getHistoryEvents(): Collection
    {
        $invitedEvents = $this->events()->with('address')->historyEvents()->orderBy('start_date_time')->get();
        $ownedEvents = $this->ownedEvents()->with('address')->historyEvents()->orderBy('start_date_time')->get();

        return $invitedEvents->merge($ownedEvents);
    }

    public function userNotSellData(): HasOne
    {
        return $this->hasOne(UserNotSellData::class);
    }
}
