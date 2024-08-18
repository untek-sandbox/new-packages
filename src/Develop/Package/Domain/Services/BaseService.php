<?php

namespace Untek\Develop\Package\Domain\Services;

use Untek\Model\Service\Interfaces\CreateEntityInterface;
use Untek\Model\Shared\Interfaces\GetEntityClassInterface;

abstract class BaseService //implements GetEntityClassInterface, CreateEntityInterface
{

//    use EventDispatcherTrait;
//    use EntityManagerAwareTrait;
//    use RepositoryAwareTrait;

    private $repository;

    /**
     * @return GetEntityClassInterface
     */
    protected function getRepository(): object
    {
        if ($this->repository) {
            return $this->repository;
        }
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityClass());
    }

    protected function setRepository(object $repository)
    {
        $this->repository = $repository;
    }

    public function getEntityClass(): string
    {
        return $this->getRepository()->getEntityClass();
    }

    /*public function createEntity(array $attributes = [])
    {
        $entityClass = $this->getEntityClass();
        return $this
            ->getEntityManager()
            ->createEntity($entityClass, $attributes);
    }*/
}