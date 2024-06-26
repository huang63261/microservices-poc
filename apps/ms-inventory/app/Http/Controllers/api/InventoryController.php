<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryRepository $inventoryRepository
    ) {}

    /**
     * Display a listing of all Inventories.
     */
    public function index(Request $request)
    {
        $inventories = $request->has('product_id')
            ? $this->inventoryRepository->findByProductId($request->input('product_id'))
            : $this->inventoryRepository->all();

        return response()->json($inventories);
    }

    public function getInventoriesBatch(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
        ]);

        $inventories = $this->inventoryRepository->findByProductIds($request->input('product_ids'));
        $inventories = array_column($inventories, null, 'product_id');

        return response()->json($inventories, Response::HTTP_OK);
    }

    /**
     * Create a new Inventory.
     */
    public function store(Request $request)
    {
        $inventory = $this->inventoryRepository->create(
            $request->validate([
                'product_id' => 'required|integer|unique:inventories,product_id',
                'locked_quantity' => 'required|integer',
                'available_quantity' => 'required|integer',
                'total_quantity' => 'required|integer',
            ])
        );

        return response()->json($inventory, Response::HTTP_CREATED);
    }

    /**
     * Display the specified Inventory.
     */
    public function show(string $productId)
    {
        return $this->inventoryRepository->findByProductId($productId);
    }

    /**
     * Update the specified Inventory in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $inventory = $this->inventoryRepository->update(
            $inventory->product_id,
            $request->validate([
                'product_id' => 'integer',
                'locked_quantity' => 'integer',
                'available_quantity' => 'integer',
                'total_quantity' => 'integer',
            ])
        );

        if (!$inventory) {
            return response()->json(['message' => 'Update failed'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($inventory);
    }

    /**
     * Remove the specified Inventory from storage.
     */
    public function destroy(Inventory $inventory)
    {
        if (!$this->inventoryRepository->delete($inventory->product_id)) {
            return response()->json(['message' => 'Delete failed'], Response::HTTP_BAD_REQUEST);
        }

        return response()->noContent();
    }
}
