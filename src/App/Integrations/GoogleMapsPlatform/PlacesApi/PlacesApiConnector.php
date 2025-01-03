<?php

namespace App\Integrations\GoogleMapsPlatform\PlacesApi;

use Saloon\Http\Connector;
use Saloon\Http\Auth\HeaderAuthenticator;

class PlacesApiConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return config('services.googleMapsPlatform.placesApi.url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    protected function defaultAuth(): HeaderAuthenticator
    {
        return new HeaderAuthenticator(
            config('services.googleMapsPlatform.key'), 'X-Goog-Api-Key'
        );
    }
}
