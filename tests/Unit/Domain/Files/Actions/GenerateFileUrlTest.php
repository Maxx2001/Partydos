<?php

namespace Tests\Unit\Domain\Files\Actions;

use Domain\Files\Actions\GenerateFileUrl;
use Domain\Files\Mappers\ModelToIdentifierMapper;
use Illuminate\Support\Facades\URL; // To mock the url() helper
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class GenerateFileUrlTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected GenerateFileUrl $action;
    protected MockInterface $mockMapper; // Mock for ModelToIdentifierMapper::map

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GenerateFileUrl();

        // Mock the static method map on ModelToIdentifierMapper
        $this->mockMapper = Mockery::mock('overload:' . ModelToIdentifierMapper::class);
        // Mock the global url() helper via its Facade/alias if available, or direct mock if truly global
        // Laravel's url() helper is typically available via Illuminate\Support\Facades\URL
        URL::shouldReceive('to')->andReturnUsing(fn($path) => 'http://localhost/' . $path)->byDefault();
    }

    public function test_it_generates_correct_file_url(): void
    {
        // Arrange
        $modelType = 'User'; // Example model type
        $modelId = 123;
        $fileId = 456;
        $fileName = 'avatar.jpg';
        $mappedIdentifier = 'user-avatar'; // Expected output from mapper for 'User'

        $this->mockMapper->shouldReceive('map')
            ->once()
            ->with($modelType)
            ->andReturn($mappedIdentifier);

        $expectedRelativePath = "{$mappedIdentifier}/{$modelId}/{$fileId}/{$fileName}";
        $expectedFullUrl = "http://localhost/{$expectedRelativePath}";

        URL::shouldReceive('to') // More specific than just url() which might be ambiguous
            ->once()
            ->with($expectedRelativePath, [], null) // Assuming default secure=null
            ->andReturn($expectedFullUrl);

        // Act
        $resultUrl = $this->action->execute($modelType, $modelId, $fileId, $fileName);

        // Assert
        $this->assertEquals($expectedFullUrl, $resultUrl);
    }

    public function test_it_generates_url_for_different_model_type(): void
    {
        // Arrange
        $modelType = 'Event';
        $modelId = 789;
        $fileId = 101;
        $fileName = 'event_banner.png';
        $mappedIdentifier = 'event-banner';

        $this->mockMapper->shouldReceive('map')
            ->once()
            ->with($modelType)
            ->andReturn($mappedIdentifier);

        $expectedRelativePath = "{$mappedIdentifier}/{$modelId}/{$fileId}/{$fileName}";
        $expectedFullUrl = "http://localhost/{$expectedRelativePath}";

        URL::shouldReceive('to')
            ->once()
            ->with($expectedRelativePath, [], null)
            ->andReturn($expectedFullUrl);

        // Act
        $resultUrl = $this->action->execute($modelType, $modelId, $fileId, $fileName);

        // Assert
        $this->assertEquals($expectedFullUrl, $resultUrl);
    }

    public function test_it_generates_url_for_unmapped_model_type(): void
    {
        // Arrange
        $modelType = 'SomeNewModel'; // A model not in the predefined map
        $modelId = 111;
        $fileId = 222;
        $fileName = 'document.pdf';
        // Based on ModelToIdentifierMapper logic: strtolower($modelName) . '-image'
        // class_basename('SomeNewModel') is 'SomeNewModel', lowercased is 'somenewmodel'
        $mappedIdentifier = 'somenewmodel-image';

        $this->mockMapper->shouldReceive('map')
            ->once()
            ->with($modelType)
            ->andReturn($mappedIdentifier); // We tell the mock what to return

        $expectedRelativePath = "{$mappedIdentifier}/{$modelId}/{$fileId}/{$fileName}";
        $expectedFullUrl = "http://localhost/{$expectedRelativePath}";

        URL::shouldReceive('to')
            ->once()
            ->with($expectedRelativePath, [], null)
            ->andReturn($expectedFullUrl);

        // Act
        $resultUrl = $this->action->execute($modelType, $modelId, $fileId, $fileName);

        // Assert
        $this->assertEquals($expectedFullUrl, $resultUrl);
    }
}
