<?php

namespace Domain\Galleries\Services;

use Domain\Users\Models\User;
use Google\Client as GoogleClient;
use Google\Service\PhotosLibrary as GooglePhotosLibraryService; // Alias to avoid conflict
use Illuminate\Support\Facades\Log;
use Exception; // Explicit import for \Exception

class GooglePhotosService
{
    private GoogleClient $client;
    private User $user;
    private ?GooglePhotosLibraryService $service = null;

    public function __construct(User $user)
    {
        $this->user = $user;
        // It's better to inject a pre-configured GoogleClient instance
        // that might have been bound in a service provider, especially if it's used elsewhere.
        // However, for this specific service tied to a user's tokens, direct instantiation is also common.
        $this->client = resolve(GoogleClient::class); // Use the one from service container

        // Ensure client has user-specific tokens and configuration
        $this->configureClient();

        $this->refreshTokenIfNeeded(); // Initial token check and refresh
        $this->service = new GooglePhotosLibraryService($this->client);
    }

    private function configureClient(): void
    {
        if (empty($this->user->google_photos_access_token)) {
            throw new Exception('User does not have a Google Photos access token. Please link your account.');
        }

        // Credentials should already be on the client if it's a singleton from the provider
        // If not, or if re-instantiating:
        // $this->client->setClientId(config('services.google.client_id'));
        // $this->client->setClientSecret(config('services.google.client_secret'));
        // No redirect_uri needed for API calls after initial auth.

        $this->client->setAccessToken([
            'access_token' => $this->user->google_photos_access_token,
            'refresh_token' => $this->user->google_photos_refresh_token,
            // expires_in should be the remaining lifetime, not an absolute timestamp here
            // However, Google Client library handles this if 'expires_at' is set correctly from initial token fetch.
            // Let's ensure the structure matches what the client expects or rely on its internal expiry check.
            // The client usually manages expiry based on 'created' + 'expires_in' or by checking isAccessTokenExpired().
        ]);

        // If google_photos_token_expires_at is a Carbon instance:
        if ($this->user->google_photos_token_expires_at &&
            $this->user->google_photos_token_expires_at->getTimestamp() > time()) {
            // This is mostly for the isAccessTokenExpired check if the client doesn't have its own expiry tracking from setAccessToken
        }
    }


    private function refreshTokenIfNeeded(): void
    {
        if ($this->client->isAccessTokenExpired()) {
            if (empty($this->user->google_photos_refresh_token)) {
                Log::error('Google Photos: Access token expired, but no refresh token available for user ' . $this->user->id);
                throw new Exception('Access token expired and no refresh token available. Please re-authenticate.');
            }

            try {
                // The refresh token is typically passed directly to fetchAccessTokenWithRefreshToken
                $this->client->fetchAccessTokenWithRefreshToken($this->user->google_photos_refresh_token);
                $newAccessToken = $this->client->getAccessToken(); // This will include the new access_token and potentially a new expires_in

                $this->user->google_photos_access_token = $newAccessToken['access_token'];
                // Google sometimes issues a new refresh token, but not always. Only update if provided.
                if (isset($newAccessToken['refresh_token'])) {
                    $this->user->google_photos_refresh_token = $newAccessToken['refresh_token'];
                }
                $this->user->google_photos_token_expires_at = now()->addSeconds($newAccessToken['expires_in']);
                $this->user->save();

                // The client instance is updated internally by fetchAccessTokenWithRefreshToken,
                // so no need to call $this->client->setAccessToken($newAccessToken) again.

            } catch (Exception $e) {
                Log::error('Google Photos: Failed to refresh access token for user ' . $this->user->id . ': ' . $e->getMessage());
                throw new Exception('Failed to refresh access token. Please re-authenticate. Details: ' . $e->getMessage());
            }
        }
    }

    public function listAlbums(array $options = []): array
    {
        $this->refreshTokenIfNeeded();
        $albums = [];
        $pageToken = null;
        $options['pageSize'] = min($options['pageSize'] ?? 50, 50); // Max 50

        try {
            do {
                $options['pageToken'] = $pageToken;
                $response = $this->service->albums->listAlbums($options);
                if ($response && $response->getAlbums()) {
                    foreach ($response->getAlbums() as $album) {
                        $albums[] = $album;
                    }
                }
                $pageToken = $response->getNextPageToken();
            } while ($pageToken);
        } catch (Exception $e) {
            Log::error('Google Photos API error while listing albums for user ' . $this->user->id . ': ' . $e->getMessage());
            throw $e;
        }
        return $albums;
    }

    public function searchMediaItems(array $searchParameters = []): array // Changed $options to $searchParameters for clarity
    {
        $this->refreshTokenIfNeeded();
        $mediaItems = [];
        $pageToken = null;
        // pageSize is part of the SearchMediaItemsRequest body, not a query param in the same way as listAlbums
        $pageSize = min($searchParameters['pageSize'] ?? 100, 100); // Max 100
        unset($searchParameters['pageSize']); // Remove from top-level array if it was there

        // The actual request body for search is an instance of Google\Service\PhotosLibrary\SearchMediaItemsRequest
        $requestBody = new \Google\Service\PhotosLibrary\SearchMediaItemsRequest($searchParameters);
        $requestBody->setPageSize($pageSize);


        try {
            do {
                $requestBody->setPageToken($pageToken);
                $response = $this->service->mediaItems->search($requestBody);
                if ($response && $response->getMediaItems()) {
                    foreach ($response->getMediaItems() as $item) {
                        $mediaItems[] = $item;
                    }
                }
                $pageToken = $response->getNextPageToken();
            } while ($pageToken);
        } catch (Exception $e) {
            Log::error('Google Photos API error while searching media items for user ' . $this->user->id . ': ' . $e->getMessage());
            throw $e;
        }
        return $mediaItems;
    }

    public function getAlbum(string $albumId): ?\Google\Service\PhotosLibrary\Album
    {
        $this->refreshTokenIfNeeded();
        try {
            return $this->service->albums->get($albumId);
        } catch (Exception $e) {
            Log::error("Google Photos API error while getting album {$albumId} for user " . $this->user->id . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function getMediaItem(string $mediaItemId): ?\Google\Service\PhotosLibrary\MediaItem
    {
        $this->refreshTokenIfNeeded();
        try {
            return $this->service->mediaItems->get($mediaItemId);
        } catch (Exception $e) {
            Log::error("Google Photos API error while getting media item {$mediaItemId} for user " . $this->user->id . ': ' . $e->getMessage());
            throw $e;
        }
    }
}
