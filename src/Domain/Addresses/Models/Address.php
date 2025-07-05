<?php

namespace Domain\Addresses\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Domain\Addresses\Models\Address
 *
 * @property int $id
 * @property string $address
 * @property string|null $place_id
 * @property string|null $location
 * @property string $addressable_type
 * @property int $addressable_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Model|Eloquent $addressable
 */

class Address extends Model
{
    protected $fillable = [
        'address',
        'place_id',
        'location'
    ];

    /**
     * @return MorphTo<Model, $this>
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
