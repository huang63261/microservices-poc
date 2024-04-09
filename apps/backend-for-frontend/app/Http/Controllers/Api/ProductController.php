<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\ProductServiceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function __construct(
        protected ProductServiceManager $productServiceManager
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return $this->productServiceManager
                ->getAllProducts($request->all());
        } catch (\Exception $e) {
            return response()->json(
                json_decode($e->getMessage())
                , $e->getCode()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return $this->productServiceManager
                ->getProductsConcurrently($id);
        } catch (\Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'message' => json_decode($e->getMessage())
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
