<?php

namespace App\Models;

use App\Traits\HasEvents;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


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
}
