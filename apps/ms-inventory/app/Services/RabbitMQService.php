<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQService
{
    public static function recieveInventoryLockingEvent($callback)
    {
        $connection = new AMQPStreamConnection(
            config('queue.connections.rabbitmq.hosts.0.host'),
            config('queue.connections.rabbitmq.hosts.0.port'),
            config('queue.connections.rabbitmq.hosts.0.user'),
            config('queue.connections.rabbitmq.hosts.0.password'),
            config('queue.connections.rabbitmq.hosts.0.vhost')
        );
        $channel = $connection->channel();

        $queueName = 'inventory-locking';
        $exchangeName = 'orchestrator';

        $channel->exchange_declare($exchangeName, 'direct', false, true, false);

        echo " [*] Waiting for logs. To exit press CTRL+C\n";

        $channel->basic_consume(queue: $queueName, callback: $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $channel->close();
        $connection->close();
    }
}