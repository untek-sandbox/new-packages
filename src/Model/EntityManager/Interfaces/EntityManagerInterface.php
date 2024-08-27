<?php

namespace Untek\Model\EntityManager\Interfaces;

use Doctrine\Common\Collections\Collection;

interface EntityManagerInterface extends TransactionInterface
{

    public function getRepository(string $entityClass): object;

    public function loadEntityRelations(object $entityOrCollection, array $with): void;

    public function remove(object $entity): void;

    public function persist(object $entity): void;

    public function insert(object $entity): void;

    public function update(object $entity): void;

    public function createEntity(string $entityClassName, array $attributes = []): object;

    public function createEntityCollection(string $entityClassName, array $items): Collection;

}
