<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function __construct(
        protected ProductRepository $ProductRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'integer',
            'name' => 'string',
            'category_id' => 'integer',
            'status' => 'array',
            'status.*' => 'integer|in:0,1,2',
        ]);

        $products = $this->ProductRepository->all(
            perPage: $request->input('per_page'),
            name: $request->input('name'),
            categoryId: $request->input('category_id'),
            status: $request->input('status'),
        );

        return new ProductCollection($products);
    }
}
