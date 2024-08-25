<?php

namespace Untek\Database\Seed\Infrastructure\Test;

use Doctrine\DBAL\Connection;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;

trait FixtureTrait
{

    protected function initializeFixtures()
    {
        $fixtures = $this->fixtures();
        if ($fixtures) {
            $importCommand = new ImportSeedCommand();
            $importCommand->setTables($fixtures);
            /** @var CommandBusInterface $bus */
            $bus = static::getContainer()->get(CommandBusInterface::class);
            /** @var Connection $connection */
            $connection = static::getContainer()->get(Connection::class);
            try {
                $bus->handle($importCommand);
                $connection->close();
            } catch (\Exception $e) {
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
