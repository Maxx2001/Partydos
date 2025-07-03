<?php

namespace Tests\Unit\Domain\Addresses\Actions;

use Domain\Addresses\Actions\UpdateAddressAction;
use Domain\Addresses\DataTransferObjects\AddressUpdateData;
use Domain\Addresses\Models\Address;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class UpdateAddressActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected UpdateAddressAction $action;
    protected MockInterface | Address $mockAddress;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateAddressAction();
        $this->mockAddress = Mockery::mock('overload:' . Address::class); // Overload for Address::find()
    }

    public function test_it_updates_address_and_sets_location(): void
    {
        // Arrange
        $addressId = 1;
        $originalAddressString = 'Old St, Old Town, Old Country';
        $updatedAddressString = '123 New St, New Town, New Country';

        $addressUpdateData = new AddressUpdateData(
            id: $addressId,
            place_id: 'new-place-id',
            address: $updatedAddressString
        );

        // Mock the Address model instance that find() will return
        $foundAddressMock = Mockery::mock(Address::class)->makePartial(); // makePartial to allow property setting
        $foundAddressMock->id = $addressId;
        $foundAddressMock->address = $originalAddressString; // Initial address before fill

        $this->mockAddress->shouldReceive('find') // Static call on overloaded class
            ->once()
            ->with($addressId)
            ->andReturn($foundAddressMock);

        $foundAddressMock->shouldReceive('fill')
            ->once()
            ->with($addressUpdateData->all())
            ->andReturnSelf(); // fill usually returns $this

        // After fill, the $foundAddressMock->address will be $updatedAddressString
        // We need to simulate this for the location processing step.
        // The action does: $address->fill(...); $address->location = ...
        // So, after fill, $foundAddressMock->address becomes $updatedAddressString.
        // Then, $this->removeCountryFromAddress($foundAddressMock->address) is called.

        // To correctly test the location logic, we ensure `address` property on mock is updated by `fill`
        // We can do this by capturing arguments or by setting the property if `fill` is complex.
        // A simpler way: assume `fill` works and sets properties.
        // So when `removeCountryFromAddress` is called, it gets the new address.
        // The action will then set `$foundAddressMock->location`.

        $foundAddressMock->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        // Act
        // We need to ensure that when $address->address is accessed after fill, it returns the new address.
        // makePartial on $foundAddressMock allows properties to be set.
        // The fill method is expected to update the 'address' property.
        // We can tap into the fill mock to simulate this:
        $foundAddressMock->shouldReceive('fill')
            ->once()
            ->with($addressUpdateData->all())
            ->andSet('address', $updatedAddressString) // Simulate that fill updates the address property
            ->andReturnSelf();


        $result = $this->action->execute($addressUpdateData);

        // Assert
        $this->assertInstanceOf(Address::class, $result);
        $this->assertEquals($addressId, $result->id);
        // Assert that the location was set correctly based on the *updated* address string
        $this->assertEquals('123 New St, New Town', $result->location, "Location should be updated and country removed.");
        // Verify the address property itself on the result (which is $foundAddressMock)
        $this->assertEquals($updatedAddressString, $result->address, "Address string should be updated.");
    }

    public function test_execute_handles_null_found_address_gracefully_or_as_expected(): void
    {
        // This test documents current behavior. Ideally, a ModelNotFoundException might be thrown.
        // Current behavior: find returns null, then code attempts to call fill() on null.
        $this->expectException(\Error::class); // Expecting "Call to a member function fill() on null"

        $addressId = 999; // Non-existent ID
        $addressUpdateData = new AddressUpdateData(
            id: $addressId,
            place_id: 'some-place-id',
            address: 'Any Address, Country'
        );

        $this->mockAddress->shouldReceive('find')
            ->once()
            ->with($addressId)
            ->andReturn(null);

        $this->action->execute($addressUpdateData); // This should trigger the error
    }
}
