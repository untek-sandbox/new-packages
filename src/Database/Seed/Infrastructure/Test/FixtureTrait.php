<?php

namespace Untek\Database\Seed\Infrastructure\Test;

use Doctrine\DBAL\Connection;
use Exception;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;

trait FixtureTrait
{

    protected function initializeFixtures()
    {
        $fixtures = $this->fixtures();
        if ($fixtures) {
            $importCommand = new ImportSeedCommand();
            $importCommand->setTables($fixtures);
            /** @var Connection $connection */
            $connection = static::getContainer()->get(Connection::class);
            try {
                $this->handleCommand($importCommand);
                $connection->close();
            } catch (Exception $e) {
                $connection->close();
                throw $e;
            }
        }
    }

    protected function fixtures(): array
    {
        return [];
    }
}
