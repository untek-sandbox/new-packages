<?php

namespace Untek\Framework\WebSocket\Application\Services;

use Untek\Framework\WebSocket\Infrastructure\Dto\SocketEvent;

interface MessageTransportInterface
{

    public function sendMessageToTcp(SocketEvent $eventEntity);
}
