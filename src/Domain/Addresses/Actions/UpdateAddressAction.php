<?php

namespace Domain\Addresses\Actions;

use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Domain\Addresses\DataTransferObjects\AddressUpdateData;
use Domain\Addresses\Models\Address;

class UpdateAddressAction
{
    public function execute(AddressUpdateData $addressCreateData): Address
    {
        $address = Address::find($addressCreateData->id);
        
        if (!$address) {
            throw new \Exception('Address not found');
        }
        
        $address->fill($addressCreateData->all());
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
