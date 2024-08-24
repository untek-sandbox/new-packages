<?php

namespace Untek\Framework\WebSocket\Infrastructure\Services;

use Untek\Framework\WebSocket\Application\Services\MessageTransportInterface;
use Untek\Framework\WebSocket\Application\Services\SocketDaemonInterface;
use Untek\Framework\WebSocket\Infrastructure\Dto\SocketEvent;

class SocketDaemonTest implements SocketDaemonInterface, MessageTransportInterface
{

    public function __construct()
    {
    }

    public function sendMessageToTcp(SocketEvent $eventEntity)
    {
        // todo: write event to file
    }

    public function runAll(bool $daemonize = false)
    {

    }
}
