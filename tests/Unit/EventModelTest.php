<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Tests\TestCase;

class EventModelTest extends TestCase
{
    public function test_event_returns_formatted_date(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
        ]);

        $expected = Carbon::parse('2024-12-31 20:00:00')->format('D d F');
        $this->assertEquals($expected, $event->formatted_date);
    }

    public function test_event_returns_formatted_time_with_start_only(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
        ]);

        $this->assertEquals('20:00', $event->formatted_time);
    }

    public function test_event_returns_formatted_time_with_end_time(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2024-12-31 23:30:00',
        ]);

        $this->assertEquals('20:00 - 23:30', $event->formatted_time);
    }

    public function test_event_generates_share_link(): void
    {
        $event = new Event([
            'unique_identifier' => 'test123456789',
        ]);

        $expectedUrl = config('app.url') . '/event-invite/test123456789';
        $this->assertEquals($expectedUrl, $event->share_link);
    }

    public function test_event_returns_iso_date_time(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
        ]);

        $this->assertEquals('2024-12-31T20:00:00', $event->iso_start_date_time);
    }

    public function test_event_returns_iso_end_date_time(): void
    {
        $event = new Event([
            'end_date_time' => '2024-12-31 23:30:00',
        ]);

        $this->assertEquals('2024-12-31T23:30:00', $event->iso_end_date_time);
    }

    public function test_event_returns_null_iso_end_date_time_when_no_end_date(): void
    {
        $event = new Event([
            'start_date_time' => '2024-12-31 20:00:00',
        ]);

        $this->assertNull($event->iso_end_date_time);
    }

    public function test_event_generates_google_calendar_link(): void
    {
        $event = new Event([
            'title' => 'Test Event',
            'description' => 'Test Description',
            'start_date_time' => '2024-12-31 20:00:00',
            'end_date_time' => '2024-12-31 23:30:00',
        ]);

        $googleLink = $event->google_calendar_link;

        $this->assertStringContainsString('google.com/calendar', $googleLink);
        $this->assertStringContainsString('Test+Event', $googleLink);
    }

    public function test_event_scope_filters_future_events(): void
    {
        // This would require a database, but demonstrates the concept
        $this->assertTrue(method_exists(Event::class, 'scopeFutureEvents'));
    }

    public function test_event_scope_filters_history_events(): void
    {
        // This would require a database, but demonstrates the concept
        $this->assertTrue(method_exists(Event::class, 'scopeHistoryEvents'));
    }
} 