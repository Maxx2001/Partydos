<?php

namespace App\Web\Auth;

use App\Support\Controllers\Controller;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse; // Correct import
use Illuminate\Support\Facades\Log;    // For logging errors
use Illuminate\Support\Facades\Redirect; // Keep this for facade usage if preferred over helper redirect()

class GoogleAuthController extends Controller
{
    private GoogleClient $googleClient;

    public function __construct(GoogleClient $googleClient)
    {
        $this->googleClient = $googleClient;
        $this->googleClient->setClientId(config('services.google.client_id'));
        $this->googleClient->setClientSecret(config('services.google.client_secret'));
        $this->googleClient->setRedirectUri(config('services.google.redirect_uri'));
        $this->googleClient->addScope('https://www.googleapis.com/auth/photoslibrary.readonly');
        $this->googleClient->setAccessType('offline'); // To get a refresh token
        $this->googleClient->setPrompt('consent');     // To ensure refresh token is provided consistently
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return Redirect::to($this->googleClient->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        if (!$request->has('code')) {
            Log::error('Google OAuth callback missing authorization code.');
            return Redirect::route('gallery.index')->with('error', 'Google authentication failed: No authorization code provided.');
        }

        try {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($request->input('code'));

            if (isset($token['error'])) {
                Log::error('Google OAuth token fetch error: ' . $token['error_description'] ?? $token['error']);
                return Redirect::route('gallery.index')->with('error', 'Google authentication failed: ' . ($token['error_description'] ?? $token['error']));
            }

            $user = Auth::user();
            if (!$user) {
                // This should not happen if route is protected by 'auth' middleware
                Log::error('Google OAuth callback: User not authenticated.');
                return Redirect::route('login')->with('error', 'Authentication required to link Google account.');
            }

            $user->google_photos_access_token = $token['access_token'];
            if (isset($token['refresh_token'])) {
                $user->google_photos_refresh_token = $token['refresh_token'];
            }
            // Ensure 'expires_in' is present before using it
            if (isset($token['expires_in'])) {
                 $user->google_photos_token_expires_at = now()->addSeconds($token['expires_in']);
            } else {
                // Default expiry if not provided, e.g. 1 hour. Or log an error.
                // Google typically provides 'expires_in'.
                Log::warning('Google OAuth token did not contain expires_in. Defaulting.');
                $user->google_photos_token_expires_at = now()->addHours(1);
            }
            $user->save();

            return Redirect::route('gallery.index')->with('success', 'Google Photos account linked successfully.');

        } catch (\Exception $e) {
            Log::error('Google OAuth callback exception: ' . $e->getMessage());
            return Redirect::route('gallery.index')->with('error', 'An unexpected error occurred during Google authentication.');
        }
    }
}
