<?php

namespace Tests\Feature\Web\Addresses;

use Domain\Addresses\Actions\GetAddressAutocompleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase; // Not strictly needed if no DB interaction here
use Mockery;
use Saloon\Exceptions\Request\FatalRequestException; // For testing exception handling
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    // RefreshDatabase might not be needed if this controller/action doesn't hit DB directly,
    // but good practice if underlying services might. For now, let's include it.
    use RefreshDatabase;

    protected MockInterface | GetAddressAutocompleteAction $mockGetAddressAction;

    protected function setUp(): void
    {
        parent::setUp();
        // Mock the action that's injected into the controller method
        $this->mockGetAddressAction = Mockery::mock(GetAddressAutocompleteAction::class);
        $this->app->instance(GetAddressAutocompleteAction::class, $this->mockGetAddressAction);
    }

    public function test_can_get_address_autocomplete_suggestions_with_valid_input(): void
    {
        $inputData = ['input' => '123 Main St'];
        $expectedSuggestions = [
            ['id' => 'place1', 'description' => '123 Main St, Anytown, USA'],
            ['id' => 'place2', 'description' => '123 Main St, Otherville, USA'],
        ];

        $this->mockGetAddressAction
            ->shouldReceive('execute')
            ->once()
            // ->with(Mockery::on(function($arg) use ($inputData) {
            //     return $arg instanceof \Domain\Addresses\DataTransferObjects\AddressAutocompleteData &&
            //            $arg->input === $inputData['input'];
            // })) // This level of argument checking for DTO is good
            ->andReturn($expectedSuggestions);

        $response = $this->postJson(route('address.autocomplete'), $inputData);

        $response->assertStatus(200)
            ->assertJson(['addresses' => $expectedSuggestions]);
    }

    public function test_autocomplete_returns_validation_error_for_missing_input(): void
    {
        $response = $this->postJson(route('address.autocomplete'), []);

        $response->assertStatus(422) // Laravel DTO validation
            ->assertJsonValidationErrors(['input']);

        $this->mockGetAddressAction->shouldNotHaveReceived('execute');
    }

    public function test_autocomplete_handles_action_exception_gracefully(): void
    {
        $inputData = ['input' => 'some address'];

        $this->mockGetAddressAction
            ->shouldReceive('execute')
            ->once()
            ->andThrow(new FatalRequestException(Mockery::mock(\Saloon\Http\Response::class), 'API connection failed'));

        $response = $this->postJson(route('address.autocomplete'), $inputData);

        // Laravel's default exception handler should turn unhandled exceptions into a 500 response
        // when APP_DEBUG is false. In testing, it might show the exception directly.
        // We can assert for a 500 if the exception handling is standard.
        $response->assertStatus(500);
    }
}
