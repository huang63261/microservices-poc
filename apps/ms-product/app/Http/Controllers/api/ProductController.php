<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $ProductRepository
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $this->ProductRepository->all(
            ...$request->validate([
                'perPage' => 'integer',
                'name' => 'string',
                'category_id' => 'integer',
                'status' => 'array',
                'status.*' => 'integer|in:0,1,2',
            ])
        );

        return new ProductCollection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = $this->ProductRepository->create([
            ...$request->validate([
                'name' => 'required|string',
                'category_id' => 'required|integer|exists:product_categories,id',
                'price' => 'required|numeric',
                'status' => 'required|integer|in:0,1,2',
                'description' => 'required|string',
            ])
        ]);

        return response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product = $this->ProductRepository->update($product->id, [
            ...$request->validate([
                'name' => 'string',
                'category_id' => 'integer|exists:product_categories,id',
                'price' => 'numeric',
                'status' => 'integer|in:0,1,2',
                'description' => 'string',
            ])
        ]);

        if (!$product) {
            return response()->json(['message' => 'Update failed'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->ProductRepository->delete($product->id);

        return response()->noContent();
    }
}
