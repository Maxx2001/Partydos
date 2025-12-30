<?php

namespace Tests\Unit\Domain\Addresses\Actions;

use App\Integrations\GoogleMapsPlatform\PlacesApi\PlacesApiConnector;
use App\Integrations\GoogleMapsPlatform\PlacesApi\Requests\CreateAutoCompleteRequest;
use Domain\Addresses\Actions\GetAddressAutocompleteAction;
use Domain\Addresses\DataTransferObjects\AddressAutocompleteData;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Saloon\Http\Response; // Assuming Saloon\Http\Response is the correct class
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class GetAddressAutocompleteActionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected GetAddressAutocompleteAction $action;
    protected Mockery\MockInterface | PlacesApiConnector $mockConnector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetAddressAutocompleteAction();
        $this->mockConnector = Mockery::mock(PlacesApiConnector::class);
        // Replace the actual connector instance with the mock for the duration of the test
        // This requires the action to be amenable to dependency injection or use a service container
        // For now, we assume we can intercept the new PlacesApiConnector() call,
        // or we refactor the action to accept the connector as a dependency.
        // A simpler way for this specific case, if PlacesApiConnector is always newed up:
        // Mockery::mock('overload:'.PlacesApiConnector::class) if we want to control its construction.
        // Let's try constructor injection for better testability if the class structure allows.
        // If not, we'll use 'overload'. For now, I'll write the test assuming the action could be refactored
        // or that we'll use an advanced Mockery feature.
        // For this example, let's assume the action is refactored to take the connector in constructor (ideal)
        // or we use a more direct approach like 'overload' if refactoring is out of scope.

        // If GetAddressAutocompleteAction cannot be easily refactored for DI:
        // $this->action = new GetAddressAutocompleteAction(); // Original instantiation
        // And then we'd need to mock 'new PlacesApiConnector()' globally or via 'overload'.
        // Let's assume for now we will mock the 'new' operator or overload the class.
    }

    public function test_it_sends_request_and_returns_dto(): void
    {
        // Arrange
        $inputString = '123 Main St';
        $addressAutocompleteData = new AddressAutocompleteData(input: $inputString);
        $expectedDto = (object)['id' => '123', 'name' => '123 Main St, Anytown']; // Example DTO

        // Mock the Saloon Response
        $mockResponse = Mockery::mock(Response::class);
        $mockResponse->shouldReceive('dto')
            ->once()
            ->andReturn($expectedDto);

        // Mock the PlacesApiConnector
        // We need to ensure that when `new PlacesApiConnector()` is called in the action,
        // our mock instance is used, or its 'send' method is globally mocked.
        $mockConnectorInstance = Mockery::mock('overload:' . PlacesApiConnector::class);
        $mockConnectorInstance->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($inputString) {
                return $request instanceof CreateAutoCompleteRequest &&
                       $request->getData()['input'] === $inputString;
            }))
            ->andReturn($mockResponse);

        $actionToTest = new GetAddressAutocompleteAction(); // Action will use the overloaded connector

        // Act
        $result = $actionToTest->execute($addressAutocompleteData);

        // Assert
        $this->assertEquals($expectedDto, $result);
    }

    public function test_it_throws_exception_when_connector_fails_fatally(): void
    {
        // Arrange
        $this->expectException(FatalRequestException::class);

        $inputString = 'test input';
        $addressAutocompleteData = new AddressAutocompleteData(input: $inputString);

        $mockConnectorInstance = Mockery::mock('overload:' . PlacesApiConnector::class);
        $mockConnectorInstance->shouldReceive('send')
            ->once()
            ->andThrow(new FatalRequestException(Mockery::mock(Response::class), 'Fatal error'));

        $actionToTest = new GetAddressAutocompleteAction();

        // Act
        $actionToTest->execute($addressAutocompleteData);
    }

    public function test_it_throws_exception_when_connector_request_fails(): void
    {
        // Arrange
        $this->expectException(RequestException::class);

        $inputString = 'another test input';
        $addressAutocompleteData = new AddressAutocompleteData(input: $inputString);

        $mockConnectorInstance = Mockery::mock('overload:' . PlacesApiConnector::class);
        $mockConnectorInstance->shouldReceive('send')
            ->once()
            ->andThrow(new RequestException(Mockery::mock(Response::class), 'Request error'));

        $actionToTest = new GetAddressAutocompleteAction();

        // Act
        $actionToTest->execute($addressAutocompleteData);
    }
}
