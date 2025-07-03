<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Domain\Addresses\Models\Address;
use Domain\Events\Actions\AuthenticatedEventCreateAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventData;
use Domain\Events\Models\Event;
use Domain\Users\Models\User;
use Illuminate\Http\Testing\File; // For mocking image upload
use Illuminate\Support\Facades\Auth as AuthFacade; // Alias to avoid conflict
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;
use Support\Services\DateAdjustmentService;

class AuthenticatedEventCreateActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected AuthenticatedEventCreateAction $action;
    protected MockInterface $mockEventModelStatics;
    protected MockInterface $mockDateService;
    protected MockInterface $mockCreateAddressAction;
    protected MockInterface $mockAttachMediaAction;
    protected MockInterface $mockNotification;
    protected MockInterface | User $mockAuthUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new AuthenticatedEventCreateAction();

        $this->mockEventModelStatics = Mockery::mock('overload:' . Event::class);
        $this->mockDateService = Mockery::mock('overload:' . DateAdjustmentService::class);
        // For actions instantiated with `new`, we mock their class if not passed via DI
        $this->mockCreateAddressAction = Mockery::mock('overload:' . CreateAddressAction::class);
        $this->mockAttachMediaAction = Mockery::mock('overload:' . AttachMediaToModelAction::class);
        $this->mockNotification = Mockery::mock('overload:' . Notification::class);

        // Mock authenticated user
        $this->mockAuthUser = Mockery::mock(User::class);
        $this->mockAuthUser->id = 1; // Example ID
        AuthFacade::shouldReceive('user')->andReturn($this->mockAuthUser);
    }

    private function getSampleEventData(bool $withLocation = false, bool $withImage = false): AuthenticatedEventData
    {
        $locationData = $withLocation ? new AddressCreateData(place_id: 'place123', address: '123 Main St, Anytown, USA') : null;
        $imageData = $withImage ? File::image('event_banner.jpg') : null;

        return new AuthenticatedEventData(
            title: 'Test Event',
            description: 'Event Description',
            location: $locationData,
            start_date_time: '2024-01-01 10:00:00',
            end_date_time: '2024-01-01 12:00:00',
            image: $imageData
        );
    }

    private function expectNotification(string $message): void
    {
        $mockNotificationChained = Mockery::mock();
        $mockNotificationChained->shouldReceive('send')->once();
        $this->mockNotification->shouldReceive('create')
            ->once()
            ->with($message)
            ->andReturn($mockNotificationChained);
    }

    public function test_it_creates_event_without_location_successfully(): void
    {
        // Arrange
        $eventData = $this->getSampleEventData(withLocation: false, withImage: true);
        $adjustedEndDate = '2024-01-01 12:00:00 adjusted'; // Example adjusted date

        $this->mockDateService->shouldReceive('adjustEndDate')
            ->once()
            ->with($eventData->start_date_time, $eventData->end_date_time)
            ->andReturn($adjustedEndDate);

        $mockEventInstance = Mockery::mock(Event::class)->makePartial();
        $mockEventInstance->shouldReceive('user->associate')->once()->with($this->mockAuthUser);
        $mockEventInstance->shouldReceive('save')->once(); // The final save
        // Note: address()->save() should NOT be called

        // Capture the data passed to Event::create to check adjusted end_date_time
        $this->mockEventModelStatics->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($eventData, $adjustedEndDate) {
                // Create a mutable copy of eventData for comparison
                $expectedData = clone $eventData;
                $expectedData->end_date_time = $adjustedEndDate; // Use the adjusted date
                return $arg == $expectedData->all();
            }))
            ->andReturn($mockEventInstance);

        $this->mockCreateAddressAction->shouldNotReceive('execute'); // No location data

        $this->mockAttachMediaAction->shouldReceive('execute')
            ->once()
            ->with([$eventData->image], $mockEventInstance, '-banner');

        $this->expectNotification('Event created!');

        // Act
        $result = $this->action->execute($eventData);

        // Assert
        $this->assertSame($mockEventInstance, $result);
    }

    public function test_it_creates_event_with_location_successfully(): void
    {
        // Arrange
        $eventData = $this->getSampleEventData(withLocation: true, withImage: false);
        $adjustedEndDate = '2024-01-01 12:00:00 adjusted';
        $mockAddressInstance = Mockery::mock(Address::class);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($adjustedEndDate);

        $mockEventInstance = Mockery::mock(Event::class)->makePartial();
        $mockEventInstance->shouldReceive('user->associate')->once()->with($this->mockAuthUser);

        // Mock address relation and its save method
        $addressRelationMock = Mockery::mock();
        $addressRelationMock->shouldReceive('save')->once()->with($mockAddressInstance);
        $mockEventInstance->shouldReceive('address')->once()->andReturn($addressRelationMock);

        $mockEventInstance->shouldReceive('save')->once(); // The final save

        $this->mockEventModelStatics->shouldReceive('create')->once()->andReturn($mockEventInstance);

        // Mock for CreateAddressAction instance
        $createAddressActionInstanceMock = Mockery::mock(CreateAddressAction::class);
        $createAddressActionInstanceMock->shouldReceive('execute')
            ->once()
            ->with($eventData->location)
            ->andReturn($mockAddressInstance);
        // Since CreateAddressAction is newed up, we need to make the 'overload' mock return our instance
        $this->mockCreateAddressAction->shouldReceive('newInstance')->andReturn($createAddressActionInstanceMock);
        // More directly if the constructor isn't an issue:
        // $this->mockCreateAddressAction = Mockery::mock('overload:'.CreateAddressAction::class, function ($mock) use ($createAddressActionInstanceMock) {
        //     return $createAddressActionInstanceMock;
        // });
        // For simplicity, if CreateAddressAction has no constructor args, the overload will work with its methods.
        // Let's assume the simpler overload for 'execute' is sufficient if constructor is empty.
        // If CreateAddressAction is newed up:
        // We need to ensure that the 'new CreateAddressAction()' call uses a mock or returns a mock.
        // The 'overload' on the class name itself should allow mocking methods on the instance.
        // So, we expect 'execute' on *any* instance of CreateAddressAction (if overloaded correctly)
        $this->mockCreateAddressAction->shouldReceive('execute')
             ->once()
             ->with($eventData->location)
             ->andReturn($mockAddressInstance);


        $this->mockAttachMediaAction->shouldReceive('execute')->once();
        $this->expectNotification('Event created!');

        // Act
        $result = $this->action->execute($eventData);

        // Assert
        $this->assertSame($mockEventInstance, $result);
    }
}
