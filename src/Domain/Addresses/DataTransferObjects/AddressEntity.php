<?php

namespace Domain\Addresses\DataTransferObjects;

use Spatie\LaravelData\Data;

class AddressEntity extends Data
{
    public function __construct(
        public int $id,
        public ?string $place_id,
        public ?string $place,
        public string $address,
        public ?string $google_maps_url = null, // Default null
    ) {
        $this->google_maps_url = $this->generateGoogleMapsUrl($place_id, $address);
    }

    private function generateGoogleMapsUrl(?string $place_id, string $address): string
    {
//        if ($place_id) {
//            return "https://www.google.com/maps/search/?api=1&query=place_id:" . $place_id;
//        }

        $encodedAddress = urlencode($address);
        return "https://www.google.com/maps/search/?api=1&query=" . $encodedAddress;
    }
}
