<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function __construct(
        protected Product $product
    ){}

    public function all(
        ?int $perPage = null,
        ?string $name = null,
        ?string $categoryId = null,
        ?array $status = null,
    ) {
        return $this->product->with('category')
            ->when($name, function($query, $name){
                return $query->where('name', 'like', "%$name%");
            })
            ->when($categoryId, function($query, $categoryId){
                return $query->where('category_id', $categoryId);
            })
            ->when($status, function($query, $status){
                return $query->whereIn('status', $status);
            })
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function find(int $id)
    {
        return $this->product->find($id);
    }

    public function findManyByIds(array $ids)
    {
        return $this->product->whereIn('id', $ids)->get();
    }

    public function update(int $id, array $data)
    {
        if (!$this->product->where('id', $id)->update($data)) {
            return false;
        }

        return $this->product->find($id);
    }

    public function delete(int $id)
    {
        return $this->product->where('id', $id)->delete();
    }
}