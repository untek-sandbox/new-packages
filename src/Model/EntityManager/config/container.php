<?php

use Psr\Container\ContainerInterface;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Libs\EntityManager;
use Untek\Model\EntityManager\Libs\EntityManagerConfigurator;

return function (ContainerConfiguratorInterface $containerConfigurator) {
    $containerConfigurator->singleton(EntityManagerInterface::class, function (ContainerInterface $container) {
        $em = EntityManager::getInstance($container);
//            $eloquentOrm = $container->get(EloquentOrm::class);
//            $em->addOrm($eloquentOrm);
        return $em;
    });

    $containerConfigurator->singleton(EntityManagerConfiguratorInterface::class, EntityManagerConfigurator::class);
};
