<?php

namespace App\Integrations\GoogleMapsPlatform\PlacesApi\Requests;

use Domain\Addresses\DataTransferObjects\SuggestionEntity;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAutoCompleteRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload
    ){}

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function resolveEndpoint(): string
    {
        return ":autocomplete";
    }

    public function createDtoFromResponse(Response $response): array
    {
        $data = $response->json();
        $formattedData = [];
        foreach ($data['suggestions'] as $suggestion) {
            $prediction = $suggestion['placePrediction'];

            if (!isset($prediction)) {
                continue;
            }

            $formattedData[] = [
                'place_id' => $prediction['placeId'],
                'place' => $prediction['place'],
                'address' => $prediction['text']['text'],
            ];
        }

        return SuggestionEntity::collect($formattedData);
    }
}
