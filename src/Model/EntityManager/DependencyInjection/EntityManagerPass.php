<?php

namespace Untek\Model\EntityManager\DependencyInjection;

use ReflectionMethod;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;
use Doctrine\Persistence\ObjectRepository;

// todo: задокументировать декларацию репозиториев

class EntityManagerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(EntityManagerConfiguratorInterface::class);
        $taggedServices = $container->findTaggedServiceIds('repository');
        foreach ($taggedServices as $repositoryInterface => $tags) {
            /** @var ObjectRepository $handlerInstance */
            if($container->has($repositoryInterface)) {
                $handlerInstance = $container->get($repositoryInterface);
                $entityClass = $handlerInstance->getClassName();
                $definition->addMethodCall('bindEntity', [$entityClass, $repositoryInterface]);
            }
        }
    }
}
