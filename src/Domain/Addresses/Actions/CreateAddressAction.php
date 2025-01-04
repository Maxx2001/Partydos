<?php

namespace Domain\Addresses\Actions;

use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Domain\Addresses\Models\Address;

class CreateAddressAction
{
    public function execute(AddressCreateData $addressCreateData): Address
    {
        $address = Address::create($addressCreateData->all());
        $address->location = $this->removeCountryFromAddress($address->address);
        $address->save();

        return $address;
    }

    private function removeCountryFromAddress(string $address): string
    {
        $parts = explode(',', $address);
        array_pop($parts);
        return implode(',', $parts);
    }
}
