<?php

namespace Untek\Database\Eloquent\Infrastructure\Abstract;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;
use Untek\Model\Contract\Interfaces\RepositoryCrudInterface;

abstract class AbstractEloquentCrudRepository extends AbstractEloquentRepository implements RepositoryCrudInterface
{

    public function findOneById(int $id, ?array $relations = null): object
    {
        $entity = $this->find($id, $relations);
        if (empty($entity)) {
            throw new NotFoundException('Entity not found!');
        }
        return $entity;
    }

    public function countBy(array $criteria): int
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select('COUNT(*) as count');
        if ($criteria) {
            EloquentQueryBuilderHelper::setWhere($criteria, $queryBuilder);
        }
        return $queryBuilder->count();
    }

    public function deleteById(int $id): void
    {
        $entity = $this->findOneById($id);
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete($id);
    }

    public function deleteByCondition(array $condition)
    {
        $queryBuilder = $this->createQueryBuilder();
        foreach ($condition as $key => $value) {
            $queryBuilder->where($key, '=', $value);
        }
        $queryBuilder->delete();
    }

    public function update(object $entity): void
    {
        $existEntity = $this->findOneById($entity->getId());
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->where('id', '=', $existEntity->getId());
        $data = $this->normalize($entity);
        $queryBuilder->update($data);
    }

    public function create(object $entity): void
    {
        $queryBuilder = $this->createQueryBuilder();
        $data = $this->normalize($entity);
        unset($data['id']);
        $lastId = $queryBuilder->insertGetId($data);
        if ($lastId) {
            $entity->setId($lastId);
        }
    }
}
