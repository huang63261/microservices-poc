<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class InventoryService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::inventory();
    }

    /**
     * Get inventory of products
     *
     * @param array $items
     */
    public function checkProductInventory(array $items)
    {
        $checkResult = $this->send('POST', '/inventories/check-availability', [
            'json' => [
                'items' => $items
            ]
        ]);

        return $checkResult;
    }
}