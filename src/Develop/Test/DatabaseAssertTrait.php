<?php

namespace Untek\Develop\Test;

use Illuminate\Database\Capsule\Manager;

trait DatabaseAssertTrait
{

    protected function getDatabaseAssert(): DatabaseAssert
    {
        /** @var Manager $capsule */
        $capsule = static::getContainer()->get(Manager::class);
        return new DatabaseAssert($capsule);
    }
}
