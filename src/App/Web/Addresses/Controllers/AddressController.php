<?php

namespace App\Web\Addresses\Controllers;

use Domain\Addresses\Actions\GetAddressAutocompleteAction;
use Domain\Addresses\DataTransferObjects\AddressAutocompleteData;
use Illuminate\Http\JsonResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Support\Controllers\Controller;

class AddressController extends Controller
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function autocomplete(AddressAutocompleteData $addressAutocompleteData, GetAddressAutocompleteAction $getAddressAutocompleteAction): JsonResponse
    {
        return response()->json([
            'addresses' => $getAddressAutocompleteAction->execute($addressAutocompleteData),
        ]);
    }
}
