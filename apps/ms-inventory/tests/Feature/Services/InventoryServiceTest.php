<?php

namespace Tests\Feature\Services;

use App\Repositories\InventoryRepository;
use App\Services\InventoryService;
use PHPUnit\Framework\TestCase;

//FIX: This test doesn't work
class InventoryServiceTest extends TestCase
{
    public function test_checkInventory_withAvailableProducts_shouldReturnSuccessResponse(): void
    {
        // Arrange
        $items = [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 3],
        ];

        $inventoryRepository = $this->createMock(InventoryRepository::class);
        $inventoryRepository->expects($this->once())
            ->method('findByProductIds')
            ->with([1, 2])
            ->willReturn([
                ['product_id' => 1, 'available_quantity' => 5],
                ['product_id' => 2, 'available_quantity' => 4],
            ]);

        $inventoryService = new InventoryService($inventoryRepository);

        // Act
        $response = $inventoryService->checkInventory($items);

        // Assert
        $this->assertEquals([
            'is_available' => 1,
            'detail' => 'All products are available',
        ], $response);
    }

    public function test_checkInventory_withInsufficientProducts_shouldThrowException(): void
    {
        // Arrange
        $items = [
            ['product_id' => 1, 'quantity' => 2],
            ['product_id' => 2, 'quantity' => 3],
        ];

        $inventoryRepository = $this->createMock(InventoryRepository::class);
        $inventoryRepository->expects($this->once())
            ->method('findByProductIds')
            ->with([1, 2])
            ->willReturn([
                ['product_id' => 1, 'available_quantity' => 1],
                ['product_id' => 2, 'available_quantity' => 2],
            ]);

        $inventoryService = new InventoryService($inventoryRepository);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product ID: 1 is insufficient. Available Quantity: 1, Product ID: 2 is insufficient. Available Quantity: 2');

        // Act
        $inventoryService->checkInventory($items);
    }
}