<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $data['links']['first'] = $this->replaceUrl($data['links']['first']);
        $data['links']['last'] = $this->replaceUrl($data['links']['last']);
        $data['links']['prev'] = $this->replaceUrl($data['links']['prev']);
        $data['links']['next'] = $this->replaceUrl($data['links']['next']);
        $data['meta']['path'] = $this->replaceUrl($data['meta']['path']);
        foreach ($data['meta']['links'] as &$link) {
            $link['url'] = $this->replaceUrl($link['url']);
        }

        return $data;
    }

    public function replaceUrl(?string $url): string|null
    {
        return $url
            ? str_replace(config('services.ms_product.api_base_url'), 'http://localhost/bff/api', $url)
            : null;
    }
}
