<?php

namespace Untek\Database\Memory\Abstract;

use Untek\Persistence\Contract\Exceptions\NotFoundException;
use Untek\Persistence\Contract\Interfaces\RepositoryCrudInterface;

abstract class AbstractMemoryCrudRepository extends AbstractMemoryRepository implements RepositoryCrudInterface
{

    protected array $collection = [];

    public function countBy(array $criteria): int
    {
        $collection = $this->findBy($criteria);
        return count($collection);
    }

    /**
     * @inheritdoc
     */
    public function findOneById(mixed $id, ?array $relations = null): object
    {
        $entity = $this->find($id, $relations);
        if (empty($entity)) {
            throw new NotFoundException('Entity not found!');
        }
        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(mixed $id): void
    {
        $entity = $this->findOneById($id);
    }

    /**
     * @inheritdoc
     */
    public function update(object $entity): void
    {
        $entity = $this->findOneById($entity->getId());
    }

    /**
     * @inheritdoc
     */
    public function create(object $entity): void
    {
        $lastId = $this->lastInsertId();
        $entity->setId($lastId + 1);
        $this->insert($entity);
    }

    protected function lastInsertId(): int
    {
        $collection = $this->findAll();
        $lastId = 0;
        foreach ($collection as $item) {
            if ($item->getId() > $lastId) {
                $lastId = $item->getId();
            }
        }
        return $lastId;
    }

    protected function insert(object $entity)
    {
        $this->loadCollection();
        $this->collection[] = $entity;
        $this->dumpCollection();
    }

    protected function dumpCollection(): void
    {

    }

    protected function loadCollection(): void
    {

    }

    protected function getItems(): array
    {
        $this->loadCollection();
        return $this->collection;
    }
}
