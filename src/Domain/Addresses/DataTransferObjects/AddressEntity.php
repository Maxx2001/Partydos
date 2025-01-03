<?php

namespace Domain\Addresses\DataTransferObjects;
use Spatie\LaravelData\Data;

class AddressEntity extends Data
{
    public function __construct(
        public int $id,
        public ?string $place_id,
        public ?string $place,
        public string  $address,
        public ?string $google_maps_url,
    ) {
        if ($place_id) {
            $this->google_maps_url = "https://www.google.com/maps/place/?q=place_id:{$place_id}";
        }
    }
}
