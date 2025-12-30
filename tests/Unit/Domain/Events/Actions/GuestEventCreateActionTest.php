<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Domain\Addresses\Models\Address;
use Domain\Events\Actions\GuestEventCreateAction;
use Domain\Events\DataTransferObjects\GuestEventCreateData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
use Domain\GuestUsers\Models\GuestUser;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Arr;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Actions\AttachMediaToModelAction;
use Support\Services\DateAdjustmentService;

class GuestEventCreateActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected GuestEventCreateAction $action;
    protected MockInterface | CreateAddressAction $mockCreateAddressAction;
    protected MockInterface | CreateOrFindGuestUserAction $mockCreateOrFindGuestUserAction;
    protected MockInterface | AttachMediaToModelAction $mockAttachMediaAction;
    protected MockInterface | DateAdjustmentService $mockDateAdjustmentService;

    protected MockInterface $mockEventModelStatics; // For Event::create

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockCreateAddressAction = Mockery::mock(CreateAddressAction::class);
        $this->mockCreateOrFindGuestUserAction = Mockery::mock(CreateOrFindGuestUserAction::class);
        $this->mockAttachMediaAction = Mockery::mock(AttachMediaToModelAction::class);
        $this->mockDateAdjustmentService = Mockery::mock(DateAdjustmentService::class);

        $this->action = new GuestEventCreateAction(
            $this->mockCreateAddressAction,
            $this->mockCreateOrFindGuestUserAction,
            $this->mockAttachMediaAction,
            $this->mockDateAdjustmentService
        );

        $this->mockEventModelStatics = Mockery::mock('overload:' . Event::class);
    }

    private function getSampleGuestEventData(bool $withLocation = false, bool $withImage = false): GuestEventCreateData
    {
        $locationData = $withLocation ? new AddressCreateData(place_id: 'guestPlace123', address: '789 Guest Ave, Anytown, USA') : null;
        $imageData = $withImage ? File::image('guest_event_banner.jpg') : null;

        return new GuestEventCreateData(
            name: 'Guest Tester',
            title: 'Guest Event Title',
            description: 'Guest Event Description',
            location: $locationData,
            start_date_time: '2024-03-01 10:00:00',
            end_date_time: '2024-03-01 12:00:00',
            image: $imageData,
            email: 'guest@example.com'
        );
    }

    public function test_it_creates_guest_event_without_location_or_image(): void
    {
        // Arrange
        $guestEventData = $this->getSampleGuestEventData(withLocation: false, withImage: false);
        $mockGuestUser = Mockery::mock(GuestUser::class);
        $adjustedEndDate = '2024-03-01 12:00:00 adjusted';

        $this->mockCreateOrFindGuestUserAction->shouldReceive('execute')
            ->once()
            ->with($guestEventData->email, $guestEventData->name)
            ->andReturn($mockGuestUser);

        $this->mockDateAdjustmentService->shouldReceive('adjustEndDate')
            ->once()
            ->with($guestEventData->start_date_time, $guestEventData->end_date_time)
            ->andReturn($adjustedEndDate);

        $this->mockCreateAddressAction->shouldNotReceive('execute');

        $mockEventInstance = Mockery::mock(Event::class)->makePartial();
        $mockEventInstance->shouldReceive('guestUser->associate')->once()->with($mockGuestUser);
        $mockEventInstance->shouldReceive('save')->once();
        // $mockEventInstance->shouldNotReceive('address'); // No address to save

        // Prepare expected data for Event::create after Arr::except and date adjustment
        $eventCreationPayload = Arr::except($guestEventData->toArray(), ['location', 'image', 'email', 'name']);
        $eventCreationPayload['end_date_time'] = $adjustedEndDate; // Use the adjusted date

        $this->mockEventModelStatics->shouldReceive('create')
            ->once()
            ->with($eventCreationPayload)
            ->andReturn($mockEventInstance);

        $this->mockAttachMediaAction->shouldNotReceive('execute');

        // Act
        $result = $this->action->execute($guestEventData);

        // Assert
        $this->assertSame($mockEventInstance, $result);
    }

    public function test_it_creates_guest_event_with_location_and_image(): void
    {
        // Arrange
        $guestEventData = $this->getSampleGuestEventData(withLocation: true, withImage: true);
        $mockGuestUser = Mockery::mock(GuestUser::class);
        $mockAddress = Mockery::mock(Address::class);
        $adjustedEndDate = '2024-03-01 12:00:00 adjusted';

        $this->mockCreateOrFindGuestUserAction->shouldReceive('execute')->once()->andReturn($mockGuestUser);
        $this->mockDateAdjustmentService->shouldReceive('adjustEndDate')->once()->andReturn($adjustedEndDate);
        $this->mockCreateAddressAction->shouldReceive('execute')
            ->once()
            ->with($guestEventData->location)
            ->andReturn($mockAddress);

        $mockEventInstance = Mockery::mock(Event::class)->makePartial();
        $mockEventInstance->shouldReceive('guestUser->associate')->once()->with($mockGuestUser);

        $addressRelationMock = Mockery::mock();
        $addressRelationMock->shouldReceive('save')->once()->with($mockAddress);
        $mockEventInstance->shouldReceive('address')->once()->andReturn($addressRelationMock);

        $mockEventInstance->shouldReceive('save')->once();

        $eventCreationPayload = Arr::except($guestEventData->toArray(), ['location', 'image', 'email', 'name']);
        $eventCreationPayload['end_date_time'] = $adjustedEndDate;
        $this->mockEventModelStatics->shouldReceive('create')->once()->with($eventCreationPayload)->andReturn($mockEventInstance);

        $this->mockAttachMediaAction->shouldReceive('execute')
            ->once()
            ->with([$guestEventData->image], $mockEventInstance, '-banner');

        // Act
        $result = $this->action->execute($guestEventData);

        // Assert
        $this->assertSame($mockEventInstance, $result);
    }
}
