<?php

namespace Tests\Unit\Domain\Addresses\Models;

use Domain\Addresses\Models\Address;
use Domain\Events\Models\Event; // Example addressable model
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase; // Useful if we test saving related models
use Tests\TestCase; // Use Laravel's TestCase for access to app features if needed

class AddressTest extends TestCase // Extend Laravel's TestCase
{
    use RefreshDatabase; // In case we decide to test saving/retrieving

    public function test_address_is_fillable(): void
    {
        $address = new Address();
        $fillable = ['address', 'place_id', 'location'];
        $this->assertEquals($fillable, $address->getFillable());
    }

    public function test_address_can_be_created_with_fillable_attributes(): void
    {
        $data = [
            'address' => '123 Main St',
            'place_id' => 'ChIJ...',
            'location' => '123 Main St, Anytown', // Assuming location is also fillable
        ];
        $address = Address::create($data); // Uses RefreshDatabase

        $this->assertDatabaseHas('addresses', $data);
        $this->assertEquals('123 Main St', $address->address);
        $this->assertEquals('ChIJ...', $address->place_id);
        $this->assertEquals('123 Main St, Anytown', $address->location);
    }

    public function test_addressable_relationship_returns_morph_to_instance(): void
    {
        $address = new Address();
        $relation = $address->addressable();
        $this->assertInstanceOf(MorphTo::class, $relation);
    }

    // Optional: Test actually setting and getting an addressable model
    public function test_address_can_be_associated_with_an_addressable_model(): void
    {
        $event = Event::factory()->create(); // Example addressable model
        $addressData = [
            'address' => 'Event Location St',
            'place_id' => 'placeEvent',
            'location' => 'Event Location St, EventCity',
        ];

        // Associate address with the event
        $address = $event->address()->create($addressData); // Assuming Event model has 'address' morphOne relationship

        $this->assertNotNull($address->addressable);
        $this->assertInstanceOf(Event::class, $address->addressable);
        $this->assertEquals($event->id, $address->addressable->id);
        $this->assertEquals('Event Location St', $address->address);
    }
}
