<?php

namespace App\Repositories;

use App\Models\Photo;

class PhotoRepository
{
    public function __construct(
        protected Photo $photo
    ) {}

    public function all()
    {
        return $this->photo->all();
    }

    public function create(array $data)
    {
        return $this->photo->create($data);
    }

    public function find(int|string $id)
    {
        return $this->photo->find($id);
    }

    /**
     * @param int $productId
     * @return array
     */
    public function findByProductId(int $productId)
    {
        return $this->photo->where('product_id', $productId)
            ->get()
            ->toArray();
    }

    public function update(int|string $id, array $data)
    {
        if (!$this->photo->where('id', $id)->update($data)) {
            return false;
        }

        return $this->photo->find($id);
    }

    public function delete(int|string $id)
    {
        return $this->photo->destroy($id);
    }
}