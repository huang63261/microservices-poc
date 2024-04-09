<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class RabbitMQService
{
    /**
     * Send event to RabbitMQ
     *
     * @param array $event
     * @param string $bindingKey
     * @param string $queueName
     * @param string $exchangeName
     */
    public static function sendEventExchange(array $event, string $bindingKey, string $queueName, string $exchangeName)
    {
        try {
            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host'),
                config('queue.connections.rabbitmq.hosts.0.port'),
                config('queue.connections.rabbitmq.hosts.0.user'),
                config('queue.connections.rabbitmq.hosts.0.password'),
                config('queue.connections.rabbitmq.hosts.0.vhost')
            );

            $channel = $connection->channel();

            $channel->exchange_declare($exchangeName, 'direct', false, true, false);
            $channel->queue_bind($queueName, $exchangeName, $bindingKey);

            $message = new AMQPMessage(
                json_encode($event),
                ['content_type' => 'plain/text', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            );

            $channel->basic_publish($message, $exchangeName, $bindingKey);

            $channel->close();
            $connection->close();
        } catch (\Exception $e) {
            Log::error('Failed to send message to RabbitMQ: ' . $e->getMessage());
        }
    }

    /**
     * Send inventory locking event to RabbitMQ
     *
     * @param string $queueName
     */
    public static function sendInventoryLockingEvent(array $event)
    {
        self::sendEventExchange($event, 'inventoryLock', 'inventory-locking', 'orchestrator');
    }

    /**
     * Send order created notification event to RabbitMQ
     *
     * @param string $queueName
     */
    public static function sendOrderCompletedEvent(array $event)
    {
        self::sendEventExchange($event, 'orderCompleted', 'notification', 'orchestrator');
    }
}
