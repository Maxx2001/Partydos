<?php

namespace Tests\Unit\Domain\Addresses\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Addresses\DataTransferObjects\AddressCreateData;
use Domain\Addresses\Models\Address;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class CreateAddressActionTest extends TestCase
{
    // Automatically integrates Mockery with PHPUnit's lifecycle
    use MockeryPHPUnitIntegration;

    protected CreateAddressAction $action;
    protected MockInterface | Address $mockAddress;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateAddressAction();
        // 'overload:' allows mocking static methods like 'create'
        $this->mockAddress = Mockery::mock('overload:' . Address::class);
    }

    public function test_it_creates_address_and_sets_location_correctly(): void
    {
        // Arrange
        $inputAddressString = '123 Main St, Anytown, USA';
        $addressCreateData = new AddressCreateData(
            place_id: 'some-place-id',
            address: $inputAddressString
        );

        // Expectations for the mocked Address model
        $this->mockAddress->shouldReceive('create')
            ->once()
            ->with($addressCreateData->all()) // DTO is spreadable, so all() gives its public properties
            ->andReturn($this->mockAddress); // Return the mock instance

        // Expect the 'location' property to be set on the mockAddress instance
        // and then 'save' to be called.
        // We capture the $this->mockAddress instance and set expectations on it.
        $this->mockAddress->address = $inputAddressString; // Simulate the state after 'create'

        $this->mockAddress->shouldReceive('save')
            ->once()
            ->withNoArgs() // save() is called with no arguments
            ->andReturn(true); // Simulate successful save

        // Act
        $result = $this->action->execute($addressCreateData);

        // Assert
        $this->assertInstanceOf(Address::class, $result);
        // Verify that the 'location' property was set as expected before save
        // The actual property assignment happens on the real object if not intercepted,
        // but here we are testing the interaction with the mock.
        // We can check the value of 'location' on the mock if we make it an attribute.
        // For this, we need to ensure the mock allows setting properties.
        // A more direct way for overloaded mocks is to check the 'save' call
        // after the property would have been set.

        // To assert the value of 'location' more directly with property assignment:
        // We can add an expectation that the 'location' property is set to the correct value.
        // However, Mockery's `shouldReceive('setAttribute')` is for when a magic `__set` or a specific method is used.
        // For direct property assignment `$this->location = $value;`, we can make the mock a partial mock
        // or use a spy. Given we're overloading, we'd expect `save` to be called on an object
        // whose `location` property *would have been* '123 Main St, Anytown'.

        // Let's refine the assertion:
        // The action does:
        // 1. $address = Address::create(...)  <- We mock this to return $this->mockAddress
        // 2. $address->location = ...  <- This sets $this->mockAddress->location
        // 3. $address->save()          <- We expect this on $this->mockAddress

        // After $this->action->execute($addressCreateData);
        // $result is $this->mockAddress.
        $this->assertEquals('123 Main St, Anytown', $result->location, "Location should have country removed.");
    }

    public function test_it_handles_address_without_comma_for_country_removal(): void
    {
        // Arrange
        $inputAddressString = 'SinglePartAddress USA';
        $addressCreateData = new AddressCreateData(
            place_id: 'another-place-id',
            address: $inputAddressString
        );

        $this->mockAddress->shouldReceive('create')
            ->once()
            ->with($addressCreateData->all())
            ->andReturn($this->mockAddress);

        $this->mockAddress->address = $inputAddressString; // Simulate state

        $this->mockAddress->shouldReceive('save')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->action->execute($addressCreateData);

        // Assert
        $this->assertInstanceOf(Address::class, $result);
        // Based on `explode(',', $address); array_pop($parts); implode(',', $parts);`
        // if $address = "SinglePartAddress USA", $parts will be ["SinglePartAddress USA"].
        // array_pop($parts) leaves $parts = []. implode(',', []) is "".
        $this->assertEquals('', $result->location, "Location should be empty if no comma before country.");
    }

    public function test_it_handles_address_with_multiple_commas_and_country(): void
    {
        // Arrange
        $inputAddressString = 'Apt 4B, 123 Main St, Complex C, Anytown, USA';
        $addressCreateData = new AddressCreateData(
            place_id: 'multi-comma-place-id',
            address: $inputAddressString
        );

        $this->mockAddress->shouldReceive('create')
            ->once()
            ->with($addressCreateData->all())
            ->andReturn($this->mockAddress);

        $this->mockAddress->address = $inputAddressString; // Simulate state

        $this->mockAddress->shouldReceive('save')
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->action->execute($addressCreateData);

        // Assert
        $this->assertInstanceOf(Address::class, $result);
        $this->assertEquals('Apt 4B, 123 Main St, Complex C, Anytown', $result->location, "Location should have only the last part (country) removed.");
    }
}
