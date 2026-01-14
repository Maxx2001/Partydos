<?php

namespace App\Services\Google;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Oauth2;
use RuntimeException;

class GoogleOAuthService
{
    /** @var array<int, string> */
    private const SCOPES = [
        'https://www.googleapis.com/auth/calendar',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ];

    public function buildAuthUrl(string $state): string
    {
        $client = $this->buildClient();
        $client->setState($state);
        $client->setScopes(self::SCOPES);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return $client->createAuthUrl();
    }

    public function fetchUserData(string $code): GoogleOAuthUserData
    {
        $client = $this->buildClient();
        $client->setScopes(self::SCOPES);

        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new RuntimeException($token['error_description'] ?? 'Unable to fetch access token.');
        }

        $client->setAccessToken($token);

        $oauth2 = new Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $expiresAt = isset($token['expires_in'])
            ? Carbon::now()->addSeconds((int) $token['expires_in'])
            : null;

        return new GoogleOAuthUserData(
            googleUserId: (string) $userInfo->getId(),
            email: $userInfo->getEmail(),
            accessToken: (string) $token['access_token'],
            refreshToken: $token['refresh_token'] ?? null,
            expiresAt: $expiresAt,
        );
    }

    private function buildClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('services.googleCalendar.client_id'));
        $client->setClientSecret(config('services.googleCalendar.client_secret'));
        $client->setRedirectUri(config('services.googleCalendar.redirect_uri'));

        return $client;
    }
}
