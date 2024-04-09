<?php

namespace App\Console\Commands;

use App\Services\MailingService;
use App\Services\RabbitMQService;
use Illuminate\Console\Command;

class ReceiveNotificationEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:receive-notification-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To consume notification event from RabbitMQ, and send email notification to customer.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        RabbitMQService::receiveNotificationEvent(function ($message) {
            $data = json_decode($message->body, true);
            $this->info('Received: ' . json_encode($data));

            MailingService::sendMail($data);

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        });
    }
}
