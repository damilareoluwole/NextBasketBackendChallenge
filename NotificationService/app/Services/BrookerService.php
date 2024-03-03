<?php

namespace App\Services;

use Stomp\Client;
use Stomp\Exception\ConnectionException;
use Stomp\Network\Connection;
use Stomp\Network\Observer\HeartbeatEmitter;
use Stomp\StatefulStomp;
use Stomp\Transport\Frame;
use Stomp\Transport\Message;

class BrookerService
{
    // The internal Stomp client
    private StatefulStomp $client;

    // A list of subscriptions held by this broker
    private array $subscriptions = [];

    public function __construct()
    {
        $connection = new Connection('tcp://' . config('activemq.host') . ":" . config('activemq.port'));

        $client = new Client($connection);

        // Once the Stomp connection and client is created, a heartbeat is added
        // to periodically let ActiveMQ know connection is alive and healthy.
        $client->setHeartbeat(500);
        $connection->setReadTimeout(0, 250000);

        // A HeartBeatEmitter is added and attached to the connection to automatically send these signals.
        $emitter = new HeartbeatEmitter($client->getConnection());
        $client->getConnection()->getObservers()->addObserver($emitter);

        // Lastly, internal Stomp client is created which is used to interact with ActiveMQ.
        $this->client = new StatefulStomp($client);
        $client->connect();
    }

    public function sendQueue(string $queueName, string $message, array $headers = [])
    {
        $destination = "/queue/$queueName";
        $this->client->send($destination, new Message($message, $headers + ['persistent' => 'true']));
    }

    public function subscribeQueue(string $queueName, ?string $selector = null): void
    {
        $destination = "/queue/$queueName";
        $this->subscriptions[$destination] = $this->client->subscribe($destination, $selector, 'client-individual');
    }

    public function unsubscribeQueue(?string $queueName = null): void
    {
        if ($queueName) {
            $destination = "/queue/$queueName";
            if (isset($this->subscriptions[$destination])) {
                $this->client->unsubscribe($this->subscriptions[$destination]);
            }
        } else {
            $this->client->unsubscribe();
        }
    }

    public function read(): ?Frame
    {
        return ($frame = $this->client->read()) ? $frame : null;
    }

    public function ack(Frame $message): void
    {
        $this->client->ack($message);
    }

    public function nack(Frame $message): void
    {
        $this->client->nack($message);
    }
}