<?php

namespace Tests\Feature\Web\Files;

use Domain\Events\Models\Event; // Assuming events can have banners
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Glide\Server;
use League\Glide\ServerFactory;
use Mockery;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->event = Event::factory()->create(['user_id' => $this->user->id]);

        // Setup a fake disk for media
        Storage::fake('public'); // Assuming 'public' is a disk used for collections like 'event-banner'
                                 // The controller uses $media->collection_name as disk.
                                 // We might need to adjust this or ensure collections use 'public'.
                                 // For this test, let's assume the collection 'event-banner' maps to 'public' disk.
    }

    private function addMediaToEvent(string $collectionName = 'event-banner'): Media
    {
        $file = UploadedFile::fake()->image('banner.jpg');
        return $this->event->addMedia($file)->toMediaCollection($collectionName);
    }

    public function test_can_show_existing_media_file(): void
    {
        $media = $this->addMediaToEvent('event-images'); // Use a specific collection name

        // Ensure the disk used by the controller matches the fake disk
        // The controller uses $media->collection_name as the disk.
        Storage::fake($media->collection_name);
        // We need to manually put the file onto the fake disk at the path Spatie MediaLibrary would use.
        // Path is $media->getKey() . '/' . $media->file_name
        $expectedPath = $media->getKey() . '/' . $media->file_name;
        Storage::disk($media->collection_name)->put($expectedPath, UploadedFile::fake()->image($media->file_name)->getContent());


        // Mock Glide Server to avoid actual image processing
        $mockGlideServer = Mockery::mock(Server::class);
        $mockGlideServer->shouldReceive('outputImage')->once()
            ->with($expectedPath, Mockery::any()); // Mockery::any() for request parameters

        $mockGlideFactory = Mockery::mock('overload:' . ServerFactory::class);
        $mockGlideFactory->shouldReceive('create')->once()->andReturn($mockGlideServer);


        $response = $this->get(route('event-banner', ['media' => $media->id]));

        $response->assertStatus(200);
        // Further assertions on headers would depend on what outputImage actually does/returns
        // For a streamed response, asserting content can be tricky. Status 200 is a good start.
    }

    public function test_show_returns_404_if_media_record_exists_but_file_not_on_disk(): void
    {
        $media = $this->addMediaToEvent('event-images-missing');
        Storage::fake($media->collection_name); // Ensure disk is faked but file not put

        $response = $this->get(route('event-banner', ['media' => $media->id]));
        $response->assertStatus(404);
    }

    public function test_show_returns_404_for_non_existing_media_record(): void
    {
        $response = $this->get(route('event-banner', ['media' => 9999])); // Non-existent media ID
        $response->assertStatus(404);
    }

    public function test_can_request_image_with_glide_params(): void
    {
        $media = $this->addMediaToEvent('event-glide-params');
        Storage::fake($media->collection_name);
        $expectedPath = $media->getKey() . '/' . $media->file_name;
        Storage::disk($media->collection_name)->put($expectedPath, UploadedFile::fake()->image($media->file_name)->getContent());

        $mockGlideServer = Mockery::mock(Server::class);
        $glideParams = ['w' => '100', 'h' => '100'];
        $mockGlideServer->shouldReceive('outputImage')->once()
            ->with($expectedPath, $glideParams); // Assert glide params are passed

        $mockGlideFactory = Mockery::mock('overload:' . ServerFactory::class);
        $mockGlideFactory->shouldReceive('create')->once()->andReturn($mockGlideServer);

        $response = $this->get(route('event-banner', ['media' => $media->id]) . '?w=100&h=100');
        $response->assertStatus(200);
    }
}
