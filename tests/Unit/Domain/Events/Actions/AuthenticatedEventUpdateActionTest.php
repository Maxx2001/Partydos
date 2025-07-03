<?php

namespace Tests\Unit\Domain\Events\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Addresses\Actions\UpdateAddressAction;
use Domain\Addresses\DataTransferObjects\AddressUpdateData;
use Domain\Addresses\Models\Address;
use Domain\Events\Actions\AuthenticatedEventUpdateAction;
use Domain\Events\DataTransferObjects\AuthenticatedEventUpdateData;
use Domain\Events\Models\Event;
use Illuminate\Http\Testing\File;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Support\Actions\AttachMediaToModelAction;
use Support\Notification;
use Support\Services\DateAdjustmentService;

class AuthenticatedEventUpdateActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected AuthenticatedEventUpdateAction $action;
    protected MockInterface | Event $mockEvent;

    // Mocks for overloaded/newed up classes
    protected MockInterface $mockDateService;
    protected MockInterface $mockUpdateAddressAction;
    protected MockInterface $mockCreateAddressAction;
    protected MockInterface $mockAttachMediaAction;
    protected MockInterface $mockNotification;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new AuthenticatedEventUpdateAction();
        $this->mockEvent = Mockery::mock(Event::class)->makePartial(); // makePartial to allow setting properties if needed

        $this->mockDateService = Mockery::mock('overload:' . DateAdjustmentService::class);
        $this->mockUpdateAddressAction = Mockery::mock('overload:' . UpdateAddressAction::class);
        $this->mockCreateAddressAction = Mockery::mock('overload:' . CreateAddressAction::class);
        $this->mockAttachMediaAction = Mockery::mock('overload:' . AttachMediaToModelAction::class);
        $this->mockNotification = Mockery::mock('overload:' . Notification::class);
    }

    private function getSampleUpdateData(array $overrides = []): AuthenticatedEventUpdateData
    {
        $defaults = [
            'title' => 'Updated Test Event',
            'description' => 'Updated Event Description',
            'location' => null, // Instance of AddressUpdateData or null
            'start_date_time' => '2024-02-01 10:00:00',
            'end_date_time' => '2024-02-01 12:00:00',
            'image' => null,
            'remove_image' => false,
        ];
        return new AuthenticatedEventUpdateData(...array_merge($defaults, $overrides));
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

    public function test_it_updates_basic_event_details_successfully(): void
    {
        // Arrange
        $updateData = $this->getSampleUpdateData(); // No location, no image changes
        $adjustedEndDate = '2024-02-01 12:00:00 adjusted';

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($adjustedEndDate);

        // Clone data for assertion, then set adjusted date
        $expectedUpdatePayload = clone $updateData;
        $expectedUpdatePayload->end_date_time = $adjustedEndDate;

        $this->mockEvent->shouldReceive('update')->once()->with($expectedUpdatePayload->all());
        $this->mockEvent->shouldReceive('save')->once();

        $this->mockUpdateAddressAction->shouldNotReceive('execute');
        $this->mockCreateAddressAction->shouldNotReceive('execute');
        $this->mockEvent->shouldNotReceive('address'); // If no location data, address relation shouldn't be touched
        $this->mockEvent->shouldNotReceive('clearMediaCollection');
        $this->mockAttachMediaAction->shouldNotReceive('execute');

        $this->expectNotification('Event updated!');

        // Act
        $result = $this->action->execute($this->mockEvent, $updateData);

        // Assert
        $this->assertSame($this->mockEvent, $result);
    }

    public function test_it_updates_location_with_existing_address(): void
    {
        // Arrange
        $locationData = new AddressUpdateData(id: 1, place_id: 'place1', address: '123 Updated St');
        $updateData = $this->getSampleUpdateData(['location' => $locationData]);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($updateData->end_date_time);
        $this->mockEvent->shouldReceive('update')->once();
        $this->mockEvent->shouldReceive('save')->once();
        $this->expectNotification('Event updated!');

        $this->mockUpdateAddressAction->shouldReceive('execute')->once()->with($locationData);
        $this->mockCreateAddressAction->shouldNotReceive('execute');
        // $this->mockEvent->shouldNotReceive('address->delete'); // This mock is a bit tricky for relation->delete()

        // Act
        $this->action->execute($this->mockEvent, $updateData);
        $this->assertTrue(true); // Assertions by Mockery
    }

    public function test_it_creates_new_location_if_address_provided_without_id(): void
    {
        // Arrange
        $locationData = new AddressUpdateData(id: null, place_id: 'placeNew', address: '456 New St');
        $updateData = $this->getSampleUpdateData(['location' => $locationData]);
        $mockNewAddress = Mockery::mock(Address::class);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($updateData->end_date_time);
        $this->mockEvent->shouldReceive('update')->once();
        $this->mockEvent->shouldReceive('save')->once();
        $this->expectNotification('Event updated!');

        $this->mockCreateAddressAction->shouldReceive('execute')->once()->with($locationData)->andReturn($mockNewAddress);

        $addressRelationMock = Mockery::mock();
        $addressRelationMock->shouldReceive('save')->once()->with($mockNewAddress);
        $this->mockEvent->shouldReceive('address')->once()->andReturn($addressRelationMock);

        $this->mockUpdateAddressAction->shouldNotReceive('execute');

        // Act
        $this->action->execute($this->mockEvent, $updateData);
        $this->assertTrue(true);
    }

    public function test_it_removes_location_if_location_data_present_but_address_is_null(): void
    {
        // Arrange
        // This case implies $authenticatedEventStoreData->location is not null,
        // but $authenticatedEventStoreData->location->address is null.
        // The AddressUpdateData DTO makes `address` nullable.
        $locationData = new AddressUpdateData(id: 1, place_id: 'place1', address: null); // Address is null
        $updateData = $this->getSampleUpdateData(['location' => $locationData]);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($updateData->end_date_time);
        $this->mockEvent->shouldReceive('update')->once();
        $this->mockEvent->shouldReceive('save')->once();
        $this->expectNotification('Event updated!');

        $addressRelationMock = Mockery::mock();
        $addressRelationMock->shouldReceive('delete')->once();
        $this->mockEvent->shouldReceive('address')->once()->andReturn($addressRelationMock);

        $this->mockCreateAddressAction->shouldNotReceive('execute');
        $this->mockUpdateAddressAction->shouldNotReceive('execute');

        // Act
        $this->action->execute($this->mockEvent, $updateData);
        $this->assertTrue(true);
    }


    public function test_it_removes_image_if_flag_is_set(): void
    {
        // Arrange
        $updateData = $this->getSampleUpdateData(['remove_image' => true, 'image' => null]);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($updateData->end_date_time);
        $this->mockEvent->shouldReceive('update')->once();
        $this->mockEvent->shouldReceive('save')->once();
        $this->expectNotification('Event updated!');

        $this->mockEvent->shouldReceive('clearMediaCollection')->once()->with('event-banner');
        $this->mockAttachMediaAction->shouldNotReceive('execute'); // No new image to attach

        // Act
        $this->action->execute($this->mockEvent, $updateData);
        $this->assertTrue(true);
    }

    public function test_it_updates_image_if_new_image_is_provided(): void
    {
        // Arrange
        $newImage = File::image('new_banner.jpg');
        $updateData = $this->getSampleUpdateData(['image' => $newImage, 'remove_image' => false]);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($updateData->end_date_time);
        $this->mockEvent->shouldReceive('update')->once();
        $this->mockEvent->shouldReceive('save')->once();
        $this->expectNotification('Event updated!');

        $this->mockEvent->shouldReceive('clearMediaCollection')->once()->with('event-banner');
        $this->mockAttachMediaAction->shouldReceive('execute')->once()->with([$newImage], $this->mockEvent, '-banner');

        // Act
        $this->action->execute($this->mockEvent, $updateData);
        $this->assertTrue(true);
    }

    public function test_it_updates_image_and_overrides_remove_image_flag_if_new_image_is_provided(): void
    {
        // Arrange
        // If a new image is provided, it should be updated, even if remove_image was true.
        // The action's logic is: if (remove_image) clear; if (image) clear, attach.
        // So clearMediaCollection might be called twice if both are true and image is present.
        // Or, more likely, the second clear just ensures it's clean before attach.
        $newImage = File::image('new_banner.jpg');
        $updateData = $this->getSampleUpdateData(['image' => $newImage, 'remove_image' => true]);

        $this->mockDateService->shouldReceive('adjustEndDate')->once()->andReturn($updateData->end_date_time);
        $this->mockEvent->shouldReceive('update')->once();
        $this->mockEvent->shouldReceive('save')->once();
        $this->expectNotification('Event updated!');

        // clearMediaCollection will be called first for remove_image=true, then for the new image.
        // So, it should be called. If it's the same collection, order might matter or just count.
        // Mockery `atLeast()` handles this flexibility.
        $this->mockEvent->shouldReceive('clearMediaCollection')->atLeast()->once()->with('event-banner');
        $this->mockAttachMediaAction->shouldReceive('execute')->once()->with([$newImage], $this->mockEvent, '-banner');

        // Act
        $this->action->execute($this->mockEvent, $updateData);
        $this->assertTrue(true);
    }
}
