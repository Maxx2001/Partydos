<?php

namespace App\Web\Integrations\Controllers;

use App\Models\EventIntegration;
use App\Models\GoogleAccount;
use App\Services\Google\GoogleCalendarClient;
use App\Services\Google\GoogleOAuthService;
use App\Web\Integrations\Requests\SetGoogleCalendarRequest;
use Domain\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;

class GoogleIntegrationController extends Controller
{
    public function index(GoogleCalendarClient $googleCalendarClient): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $googleAccount = $user->googleAccount;
        $calendars = [];

        if ($googleAccount !== null) {
            try {
                $calendars = $googleCalendarClient->getCalendars($user);
            } catch (\Throwable) {
                $googleAccount = $user->fresh()->googleAccount;
            }
        }

        return Inertia::render('Integrations/Google', [
            'googleAccount' => $googleAccount
                ? [
                    'email' => $googleAccount->email,
                    'calendarId' => $googleAccount->calendar_id,
                ]
                : null,
            'calendars' => $calendars,
        ]);
    }

    public function redirect(GoogleOAuthService $googleOAuthService): RedirectResponse
    {
        $state = Str::random(40);
        Session::put('google_oauth_state', $state);

        return redirect()->away($googleOAuthService->buildAuthUrl($state));
    }

    public function callback(GoogleOAuthService $googleOAuthService): RedirectResponse
    {
        $state = request()->string('state');

        if (! $state->value() || $state->value() !== Session::pull('google_oauth_state')) {
            abort(403);
        }

        $code = request()->string('code');

        if (! $code->value()) {
            return redirect()->route('integrations.google.index')
                ->with('error', 'Google connection failed.');
        }

        /** @var User $user */
        $user = Auth::user();

        $oauthData = $googleOAuthService->fetchUserData($code->value());

        $account = GoogleAccount::firstOrNew(['user_id' => $user->id]);
        $account->fill([
            'google_user_id' => $oauthData->googleUserId,
            'email' => $oauthData->email,
            'access_token' => $oauthData->accessToken,
            'refresh_token' => $oauthData->refreshToken ?? $account->refresh_token,
            'token_expires_at' => $oauthData->expiresAt,
            'calendar_id' => $account->calendar_id ?? 'primary',
        ]);
        $account->save();

        return redirect()->route('integrations.google.index')
            ->with('success', 'Google account connected.');
    }

    public function disconnect(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->googleAccount?->delete();

        EventIntegration::where('provider', 'google')
            ->whereHas('event', fn ($query) => $query->where('user_id', $user->id))
            ->delete();

        return redirect()->route('integrations.google.index')
            ->with('success', 'Google account disconnected.');
    }

    public function setCalendar(SetGoogleCalendarRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $googleAccount = $user->googleAccount;

        if ($googleAccount === null) {
            return redirect()->route('integrations.google.index')
                ->with('error', 'Connect Google before selecting a calendar.');
        }

        $googleAccount->update([
            'calendar_id' => $request->validated('calendar_id'),
        ]);

        return redirect()->route('integrations.google.index')
            ->with('success', 'Default calendar updated.');
    }
}
