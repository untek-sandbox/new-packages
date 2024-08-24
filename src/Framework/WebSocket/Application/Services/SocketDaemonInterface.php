<?php

namespace Untek\Framework\WebSocket\Application\Services;

interface SocketDaemonInterface
{

    public function runAll(bool $daemonize = false);
}
