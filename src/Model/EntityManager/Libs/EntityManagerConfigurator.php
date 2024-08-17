<?php

namespace Untek\Model\EntityManager\Libs;

use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;

class EntityManagerConfigurator implements EntityManagerConfiguratorInterface
{

    private $entityToRepository = [];

    public function bindEntity(string $entityClass, string $repositoryInterface): void
    {
        $this->entityToRepository[$entityClass] = $repositoryInterface;
    }

    public function getConfig(): array
    {
        return $this->entityToRepository;
    }

    public function setConfig(array $config): void
    {
        $this->entityToRepository = $config;
    }

    public function entityToRepository(string $entityClass)
    {
        return $this->entityToRepository[$entityClass] ?? null;
    }
}
