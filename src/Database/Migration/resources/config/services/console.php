<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Base\Domain\Repositories\Eloquent\SchemaRepository;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Migration\Infrastructure\Persistence\Eloquent\Repository\HistoryRepository;
use Untek\Database\Migration\Infrastructure\Persistence\FileSystem\Repository\SourceRepository;
use Untek\Database\Migration\Application\Services\MigrationService;
use Untek\Database\Migration\Presentation\Cli\Commands\DownCommand;
use Untek\Database\Migration\Presentation\Cli\Commands\UpCommand;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Libs\EntityManager;
use Untek\Model\EntityManager\Libs\EntityManagerConfigurator;
use Doctrine\DBAL\Connection;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();
    
    /*$services->set(DownCommand::class, DownCommand::class)
        ->args([
            service(MigrationService::class)
        ])
        ->tag('console.command');

    $services->set(UpCommand::class, UpCommand::class)
        ->args([
            service(MigrationService::class)
        ])
        ->tag('console.command');*/
};