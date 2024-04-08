<?php

namespace App\Console\Commands;

use App\Services\InventoryService;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class ReceiveInventoryLock extends Command
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:receive-inventory-lock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive inventory lock event from RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $callback = function ($message) {
            $data = json_decode($message->body, true);
            $this->info('Received: ' . json_encode($data));

            // Inventory Locking
            $this->inventoryService->lockInventory($data);

            // Acknowledge the message
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

        RabbitMQService::recieveInventoryLockingEvent($callback);
    }
}
