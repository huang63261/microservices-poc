<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepository
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'product_id' => 'array',
        ]);

        $products = $this->productRepository
            ->findManyByIds($request->input('product_id'));

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = $this->productRepository->create([
            ...$request->validate([
                'name' => 'required|string',
                'category_id' => 'required|integer|exists:product_categories,id',
                'price' => 'required|numeric',
                'status' => 'required|integer|in:0,1,2',
                'description' => 'required|string',
            ])
        ]);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product = $this->productRepository->update($product->id, [
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

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productRepository->delete($product->id);

        return response()->noContent();
    }
}
