<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Repositories\PhotoRepository;
use App\Services\Contracts\StorageService;
use App\Services\LocalStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the PhotoRepository and StorageService bindings
        $this->app->bind(PhotoRepository::class, function ($app) {
            return $this->createMock(PhotoRepository::class);
        });

        $this->app->bind(StorageService::class, function ($app) {
            return $this->createMock(LocalStorageService::class);
        });
    }

    public function test_index_returns_all_photos()
    {
        $photos = Photo::factory(3)->create();

        $this->mock(PhotoRepository::class)
            ->shouldReceive('all')
            ->once()
            ->andReturn($photos);

        $response = $this->get(route('photos.index'));

        $response->assertStatus(200)
            ->assertJson($photos->toArray());
    }

    public function test_index_returns_photos_by_product_id()
    {
        $productId = 1;
        $photos = Photo::factory(2)->create(['product_id' => $productId]);

        $this->mock(PhotoRepository::class)
            ->shouldReceive('findByProductId')
            ->once()
            ->with($productId)
            ->andReturn($photos);

        $response = $this->get(route('photos.index', ['product_id' => $productId]));

        $response->assertStatus(200)
            ->assertJson($photos->toArray());
    }

    public function test_store_creates_new_photo()
    {
        Storage::fake('photos');

        $photoData = [
            'product_id' => 1,
            'photo' => UploadedFile::fake()->image('photo.jpg')
        ];

        $this->mock(StorageService::class)
            ->shouldReceive('store')
            ->once()
            ->with($photoData['photo'], 'photos')
            ->andReturn('path/to/photo.jpg');

        $this->mock(PhotoRepository::class)
            ->shouldReceive('create')
            ->once()
            ->with([
                'product_id' => $photoData['product_id'],
                'uri' => 'path/to/photo.jpg'
            ])
            ->andReturn(new Photo([
                'product_id' => $photoData['product_id'],
                'uri' => 'path/to/photo.jpg'
            ]));

        $response = $this->post(route('photos.store'), $photoData);

        $response->assertStatus(201)
            ->assertJson([
                'product_id' => $photoData['product_id'],
                'uri' => 'path/to/photo.jpg'
            ]);
    }

    public function test_show_returns_photo()
    {
        $photo = Photo::factory()->create();

        $response = $this->get(route('photos.show', $photo->id));

        $response->assertStatus(200)
            ->assertJson($photo->toArray());
    }

    public function test_update_updates_photo()
    {
        Storage::fake('public');

        $photo = Photo::factory()->create();
        $newPhoto = UploadedFile::fake()->image('new_photo.jpg');
        $mockStorageService = $this->mock(StorageService::class);

        $mockStorageService
            ->shouldReceive('delete')
            ->once()
            ->with($photo->uri)
            ->andReturnTrue();

        $mockStorageService
            ->shouldReceive('store')
            ->once()
            ->with($newPhoto, 'photos')
            ->andReturn('path/to/new_photo.jpg');

        $this->mock(PhotoRepository::class)
            ->shouldReceive('update')
            ->once()
            ->with($photo->id, ['uri' => 'path/to/new_photo.jpg'])
            ->andReturn($photo);

        $response = $this->put(
            route('photos.update', $photo->id),
            ['photo' => $newPhoto]
        );

        $response->assertStatus(200)
            ->assertJson($photo->toArray());
    }

    public function test_update_fails_when_no_photo_provided()
    {
        $photo = Photo::factory()->create();

        $response = $this->put(
            route('photos.update', $photo->id),
            []
        );

        $response->assertStatus(400)
            ->assertJson(['message' => 'Update failed.']);
    }

    public function test_destroy_deletes_photo()
    {
        $photo = Photo::factory()->create();

        $this->mock(StorageService::class)
            ->shouldReceive('delete')
            ->once()
            ->with($photo->uri);

        $this->delete(route('photos.destroy', $photo->id))
            ->assertStatus(204);
    }
}
