<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductReviewResource;
use App\Repositories\ProductReviewRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductReviewController extends Controller
{
    public function __construct(
        protected ProductReviewRepository $productReviewRepository
    ) {}

    /**
     * Get all product reviews.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing all product reviews.
     */
    public function index(Request $request)
    {
        $productReviews = $request->has('product_id')
            ? $this->productReviewRepository->findByProductId($request->input('product_id'))
            : $this->productReviewRepository->all();

        return response()->json($productReviews);
    }

    /**
     * Get product reviews by product IDs.
     *
     * @param Request $request The HTTP request.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the product reviews.
     */
    public function getReviewsBatch(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer',
        ]);

        $productReviews = $this->productReviewRepository->findByProductIds($request->input('product_ids'));
        $productReviews = collect($productReviews)->groupBy('product_id')->toArray();

        ksort($productReviews);

        return response()->json($productReviews);
    }

    /**
     * Create a new product review.
     *
     * @param Request $request The HTTP request.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the created product review.
     */
    public function store(Request $request)
    {
        $productReview = $this->productReviewRepository->create([
            ...$request->validate([
                'product_id' => 'required|integer',
                'customer_id' => 'required|integer',
                'rating' => 'required|integer|between:1,5',
                'content' => 'required|string',
            ])
        ]);

        return response()->json($productReview, Response::HTTP_CREATED);
    }

    /**
     * Get a specific product review by ID.
     *
     * @param int $id The product review ID.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the product review.
     */
    public function show($id)
    {
        $productReview = $this->productReviewRepository->find($id);

        return response()->json($productReview);
    }

    /**
     * Update a product review.
     *
     * @param Request $request The HTTP request.
     * @param int $id The product review ID.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated product review or a failure response code.
     */
    public function update(Request $request, $id)
    {
        $productReview = $this->productReviewRepository->update(
            $request->validate([
            'product_id' => 'integer',
            'customer_id' => 'integer',
            'rating' => 'integer|between:1,5',
            'content' => 'string',
        ]), $id);

        if (!$productReview) {
            return response()->json(['message' => 'Update failed'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json($productReview);
    }

    /**
     * Delete a product review.
     *
     * @param int $id The product review ID.
     * @return \Illuminate\Http\JsonResponse The JSON response indicating the success of the deletion.
     */
    public function destroy($id)
    {
        $this->productReviewRepository->delete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}