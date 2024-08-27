<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Libs\EntityManager;
use Untek\Model\EntityManager\Libs\EntityManagerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(EntityManagerConfiguratorInterface::class, EntityManagerConfigurator::class);
    $services->set(EntityManagerInterface::class, EntityManager::class)
        ->args(
            [
                service(ContainerInterface::class),
                service(EntityManagerConfiguratorInterface::class),
            ]
        );
};