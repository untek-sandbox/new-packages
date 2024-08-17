<?php

namespace Untek\Model\EntityManager\Interfaces;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\Repository\Interfaces\FindOneUniqueInterface;
use Untek\Model\Repository\Interfaces\RepositoryInterface;

interface EntityManagerInterface extends TransactionInterface//, FindOneUniqueInterface
{

    public function getRepository(string $entityClass): object;

    public function loadEntityRelations(object $entityOrCollection, array $with): void;

    public function remove(object $entity): void;

    public function persist(object $entity): void;

    public function insert(object $entity): void;

    public function update(object $entity): void;

    public function createEntity(string $entityClassName, array $attributes = []): object;

    public function createEntityCollection(string $entityClassName, array $items): Enumerable;

}
