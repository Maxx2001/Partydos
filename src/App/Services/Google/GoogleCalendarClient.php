<?php

namespace App\Services\Google;

use App\Models\EventIntegration;
use App\Models\GoogleAccount;
use Carbon\Carbon;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event as GoogleEvent;
use Google\Service\Exception as GoogleServiceException;
use RuntimeException;

class GoogleCalendarClient
{
    /** @var array<int, string> */
    private const SCOPES = [
        'https://www.googleapis.com/auth/calendar',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ];

    /** @return array<int, array{id: string, summary: string}> */
    public function getCalendars(User $user): array
    {
        $service = new Calendar($this->getAuthorizedClient($user));

        try {
            $calendarList = $service->calendarList->listCalendarList();
        } catch (GoogleServiceException $exception) {
            $this->handleGoogleException($user, $exception);
            throw $exception;
        }

        $calendars = [];

        foreach ($calendarList->getItems() as $calendar) {
            $calendars[] = [
                'id' => (string) $calendar->getId(),
                'summary' => (string) $calendar->getSummary(),
            ];
        }

        return $calendars;
    }

    public function upsertEvent(User $user, Event $event): EventIntegration
    {
        $account = $this->getGoogleAccount($user);
        $event->loadMissing('address', 'googleIntegration');

        $calendarService = new Calendar($this->getAuthorizedClient($user));
        $integration = EventIntegration::firstOrNew([
            'event_id' => $event->id,
            'provider' => 'google',
        ]);

        $calendarId = $integration->external_calendar_id ?: $account->calendar_id;
        $syncHash = $this->buildSyncHash($event);

        if ($integration->exists && $integration->external_event_id && $integration->last_sync_hash === $syncHash) {
            return $integration;
        }

        $googleEvent = $this->buildGoogleEvent($event);

        try {
            if ($integration->external_event_id) {
                $updatedEvent = $calendarService->events->update(
                    $calendarId,
                    $integration->external_event_id,
                    $googleEvent
                );
                $externalEventId = (string) $updatedEvent->getId();
            } else {
                $createdEvent = $calendarService->events->insert($calendarId, $googleEvent);
                $externalEventId = (string) $createdEvent->getId();
            }
        } catch (GoogleServiceException $exception) {
            $this->handleGoogleException($user, $exception);
            throw $exception;
        }

        $integration->fill([
            'external_calendar_id' => $calendarId,
            'external_event_id' => $externalEventId,
            'sync_enabled' => $integration->sync_enabled ?? true,
            'last_synced_at' => now(),
            'last_sync_hash' => $syncHash,
        ]);

        $integration->save();

        return $integration;
    }

    public function deleteEvent(User $user, Event $event): void
    {
        $event->loadMissing('googleIntegration');

        if ($event->googleIntegration === null) {
            return;
        }

        $calendarService = new Calendar($this->getAuthorizedClient($user));
        $calendarId = $event->googleIntegration->external_calendar_id;

        try {
            $calendarService->events->delete($calendarId, $event->googleIntegration->external_event_id);
        } catch (GoogleServiceException $exception) {
            $this->handleGoogleException($user, $exception);
            throw $exception;
        }

        $event->googleIntegration->delete();
    }

    private function buildGoogleEvent(Event $event): GoogleEvent
    {
        $timezone = config('app.timezone');
        $start = Carbon::parse($event->start_date_time, $timezone);
        $end = $event->end_date_time
            ? Carbon::parse($event->end_date_time, $timezone)
            : $start->copy()->addHours(4);

        return new GoogleEvent([
            'summary' => $event->title,
            'description' => $this->buildDescription($event),
            'location' => $event->address?->address,
            'start' => [
                'dateTime' => $start->toRfc3339String(),
                'timeZone' => $timezone,
            ],
            'end' => [
                'dateTime' => $end->toRfc3339String(),
                'timeZone' => $timezone,
            ],
        ]);
    }

    private function buildDescription(Event $event): string
    {
        $parts = [];

        if ($event->description) {
            $parts[] = $event->description;
        }

        $parts[] = 'View in Partydos: ' . route('events.show-invite', ['event' => $event->unique_identifier]);

        return implode("\n\n", $parts);
    }

    private function buildSyncHash(Event $event): string
    {
        $timezone = config('app.timezone');

        return hash('sha256', implode('|', [
            $event->title,
            (string) $event->description,
            (string) $event->start_date_time,
            (string) $event->end_date_time,
            (string) ($event->address?->address ?? ''),
            (string) $timezone,
        ]));
    }

    private function getAuthorizedClient(User $user): Client
    {
        $account = $this->getGoogleAccount($user);
        $client = $this->buildClient();
        $client->setAccessToken($account->access_token);

        if ($account->token_expires_at !== null && $account->token_expires_at->isPast()) {
            if (! $account->refresh_token) {
                $this->disconnectAccount($account);
                throw new RuntimeException('Google refresh token is missing.');
            }

            $token = $client->fetchAccessTokenWithRefreshToken($account->refresh_token);

            if (isset($token['error'])) {
                $this->disconnectAccount($account);
                throw new RuntimeException($token['error_description'] ?? 'Unable to refresh Google token.');
            }

            $account->forceFill([
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? $account->refresh_token,
                'token_expires_at' => isset($token['expires_in'])
                    ? Carbon::now()->addSeconds((int) $token['expires_in'])
                    : $account->token_expires_at,
            ])->save();

            $client->setAccessToken($account->access_token);
        }

        return $client;
    }

    private function buildClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('services.googleCalendar.client_id'));
        $client->setClientSecret(config('services.googleCalendar.client_secret'));
        $client->setRedirectUri(config('services.googleCalendar.redirect_uri'));
        $client->setScopes(self::SCOPES);

        return $client;
    }

    private function getGoogleAccount(User $user): GoogleAccount
    {
        $account = $user->googleAccount;

        if ($account === null) {
            throw new RuntimeException('Google account is not connected.');
        }

        return $account;
    }

    private function handleGoogleException(User $user, GoogleServiceException $exception): void
    {
        if (in_array($exception->getCode(), [401, 403], true)) {
            $account = $user->googleAccount;
            if ($account !== null) {
                $this->disconnectAccount($account);
            }
        }
    }

    private function disconnectAccount(GoogleAccount $account): void
    {
        $account->delete();
    }
}
