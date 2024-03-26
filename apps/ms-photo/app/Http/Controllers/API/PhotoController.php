<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Repositories\PhotoRepository;
use App\Services\Contracts\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PhotoController extends Controller
{
    public function __construct(
        protected PhotoRepository $photoRepository,
        protected StorageService $storageService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'product_id' => 'integer'
        ]);

        $photos = $request->has('product_id')
            ? $this->photoRepository->findByProductId($request->product_id)
            : $this->photoRepository->all();

        return response()->json($photos, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'photo' => 'required|image'
        ]);

        try {
            $path = $this->storageService->store($request->file('photo'), 'photos');
            $photoData = [
                'product_id' => $request->product_id,
                'uri' => $path
            ];
            $photo = $this->photoRepository->create($photoData);

            if (!$photo) {
                $this->storageService->delete($path);
                throw new \Exception('Failed to create photo record in the database.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($photo, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        return response()->json($photo, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        if ($request->hasFile('photo')) {
            $this->storageService->delete($photo->uri);

            $path = $this->storageService->store($request->file('photo'), 'photos');

            $updatedPhoto = $this->photoRepository->update($photo->id, [
                'uri' => $path
            ]);

            if ($updatedPhoto) {
                return response()->json($updatedPhoto, Response::HTTP_OK);
            }
        }

        return response()->json(['message' => 'Update failed.'], Response::HTTP_BAD_REQUEST);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        $this->storageService->delete($photo->uri);
        $photo->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
