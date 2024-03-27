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

    public function find(string $id)
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

    /**
     * @param array $productIds
     * @return array
     */
    public function findByProductIds(array $productIds)
    {
        return $this->photo->whereIn('product_id', $productIds)
            ->get()
            ->toArray();
    }

    public function update(string $id, array $data)
    {
        if (!$this->photo->where('id', $id)->update($data)) {
            return false;
        }

        return $this->photo->find($id);
    }

    public function delete(string $id)
    {
        return $this->photo->destroy($id);
    }
}