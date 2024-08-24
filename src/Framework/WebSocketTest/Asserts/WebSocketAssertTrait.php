<?php

namespace Untek\Framework\WebSocketTest\Asserts;

use Illuminate\Database\Capsule\Manager;

trait WebSocketAssertTrait
{

    protected function getWebSocketAssert(): WebSocketAssert
    {
        /** @var Manager $capsule */
        $capsule = static::getContainer()->get(Manager::class);
        return new WebSocketAssert($capsule);
    }
}
