<?php

namespace Domain\Addresses\Actions;

use App\Integrations\GoogleMapsPlatform\PlacesApi\PlacesApiConnector;
use App\Integrations\GoogleMapsPlatform\PlacesApi\Requests\CreateAutoCompleteRequest;
use Domain\Addresses\DataTransferObjects\AddressAutocompleteData;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class GetAddressAutocompleteAction
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function execute(AddressAutocompleteData $addressAutocompleteData)
    {
        $connector = new PlacesApiConnector();

        return $connector->send(new CreateAutoCompleteRequest([
            'input' => $addressAutocompleteData->input,
        ]))->dto();
    }
}
