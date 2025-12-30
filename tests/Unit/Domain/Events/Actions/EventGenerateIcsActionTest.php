<?php

namespace Tests\Unit\Domain\Events\Actions;

use Carbon\Carbon;
use Domain\Events\Actions\EventGenerateIcsAction;
use Domain\Events\Models\Event;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class EventGenerateIcsActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected EventGenerateIcsAction $action;
    protected MockInterface | Event $mockEvent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new EventGenerateIcsAction();
        $this->mockEvent = Mockery::mock(Event::class);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // Reset Carbon's test now
        parent::tearDown();
    }

    public function test_it_generates_correct_ics_string(): void
    {
        // Arrange
        $startTime = Carbon::create(2024, 3, 10, 14, 30, 0, 'UTC');
        // The current action code uses start_date_time for both DTSTART and DTEND.
        // If event->end_date_time was intended, this mock and test would change.
        $endTimeForTest = $startTime; // Based on current action logic for DTEND

        $this->mockEvent->title = "My Awesome Event";
        $this->mockEvent->description = "This is a detailed description.\nIt has newlines.";
        $this->mockEvent->location = "123 Main St, Anytown";
        $this->mockEvent->start_date_time = $startTime->toDateTimeString();
        // Let's assume event->end_date_time exists, even if not used by DTEND in current action code
        $this->mockEvent->end_date_time = $startTime->addHours(2)->toDateTimeString();


        $frozenNow = Carbon::create(2024, 3, 1, 10, 0, 0, 'UTC');
        Carbon::setTestNow($frozenNow);

        // Act
        $icsString = $this->action->execute($this->mockEvent);

        // Assert
        $this->assertStringContainsString("BEGIN:VCALENDAR", $icsString);
        $this->assertStringContainsString("VERSION:2.0", $icsString);
        $this->assertStringContainsString("PRODID:-//partydos.nl//NONSGML v1.0//EN", $icsString);
        $this->assertStringContainsString("BEGIN:VEVENT", $icsString);
        $this->assertMatchesRegularExpression("/UID:[a-zA-Z0-9]+@partydos.nl/", $icsString); // Check for UID format
        $this->assertStringContainsString("DTSTAMP:" . $frozenNow->format('Ymd\THis'), $icsString);
        $this->assertStringContainsString("DTSTART:" . $startTime->format('Ymd\THis'), $icsString);
        // Current action logic uses start_date_time for DTEND
        $this->assertStringContainsString("DTEND:" . $endTimeForTest->format('Ymd\THis'), $icsString);
        $this->assertStringContainsString("SUMMARY:" . addslashes($this->mockEvent->title), $icsString);
        $this->assertStringContainsString("DESCRIPTION:" . addslashes($this->mockEvent->description), $icsString);
        $this->assertStringContainsString("LOCATION:" . addslashes($this->mockEvent->location), $icsString);
        $this->assertStringContainsString("END:VEVENT", $icsString);
        $this->assertStringContainsString("END:VCALENDAR", $icsString);
    }

    public function test_it_handles_null_description_and_location(): void
    {
        // Arrange
        $startTime = Carbon::create(2024, 3, 10, 14, 30, 0, 'UTC');
        $this->mockEvent->title = "Event Without Details";
        $this->mockEvent->description = null;
        $this->mockEvent->location = null;
        $this->mockEvent->start_date_time = $startTime->toDateTimeString();
        $this->mockEvent->end_date_time = $startTime->addHours(1)->toDateTimeString(); // Even if not used for DTEND

        Carbon::setTestNow(Carbon::now()); // Freeze time

        // Act
        $icsString = $this->action->execute($this->mockEvent);

        // Assert
        $this->assertStringContainsString("SUMMARY:" . addslashes($this->mockEvent->title), $icsString);
        $this->assertStringContainsString("DESCRIPTION:", $icsString); // addslashes(null) is ""
        $this->assertStringContainsString("LOCATION:", $icsString);   // addslashes(null) is ""
        $this->assertStringNotContainsString("DESCRIPTION:null", $icsString);
        $this->assertStringNotContainsString("LOCATION:null", $icsString);
    }
}
