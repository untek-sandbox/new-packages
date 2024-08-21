<?php

namespace Untek\Database\Eloquent\Domain\Base;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Database\Base\Domain\Traits\TableNameTrait;
use Illuminate\Database\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Helpers\QueryBuilder\EloquentQueryBuilderHelper;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Traits\EntityManagerAwareTrait;
use Untek\Model\Repository\Traits\RepositoryDispatchEventTrait;
use Untek\Model\Repository\Traits\RepositoryMapperTrait;
use Untek\Model\Repository\Traits\RepositoryQueryTrait;
use Untek\Model\Shared\Interfaces\GetEntityClassInterface;
use Untek\Model\Shared\Traits\DispatchEventTrait;
use Untek\Model\Shared\Traits\ForgeQueryTrait;

abstract class BaseEloquentRepository //implements GetEntityClassInterface
{

    use EloquentTrait;
    use TableNameTrait;
    use EntityManagerAwareTrait;
//    use RepositoryMapperTrait;
//    use DispatchEventTrait;
//    use ForgeQueryTrait;

    protected function forgeQuery(Query $query = null): Query
    {
        $query = Query::forge($query);
//        $this->dispatchQueryEvent($query, EventEnum::BEFORE_FORGE_QUERY);
        return $query;
    }

    public function __construct(EntityManagerInterface $em, Manager $capsule)
    {
        $this->setCapsule($capsule);
        $this->setEntityManager($em);
    }

    protected function forgeQueryBuilder(QueryBuilder $queryBuilder, Query $query)
    {
//        $queryBuilder = $queryBuilder ?? $this->getQueryBuilder();
        EloquentQueryBuilderHelper::setWhere($query, $queryBuilder);
        EloquentQueryBuilderHelper::setJoin($query, $queryBuilder);
//        return
    }

    protected function getQueryBuilder(): QueryBuilder
    {
        $connection = $this->getCapsule()->getConnection();
        return $connection->table($this->getTableName());

//        return $this->getQueryBuilderByTableName($this->getTableName());
    }

    protected function findBy(Query $query = null): Enumerable
    {
        $query = $this->forgeQuery($query);
        $queryBuilder = $this->getQueryBuilder();
        $this->forgeQueryBuilder($queryBuilder, $query);
        $query->select([$queryBuilder->from . '.*']);
//        EloquentQueryBuilderHelper::setWhere($query, $queryBuilder);
//        EloquentQueryBuilderHelper::setJoin($query, $queryBuilder);
        EloquentQueryBuilderHelper::setSelect($query, $queryBuilder);
        EloquentQueryBuilderHelper::setOrder($query, $queryBuilder);
        EloquentQueryBuilderHelper::setGroupBy($query, $queryBuilder);
        EloquentQueryBuilderHelper::setPaginate($query, $queryBuilder);
        $collection = $this->findByBuilder($queryBuilder);
        return $collection;
    }

    protected function findByBuilder(QueryBuilder $queryBuilder): Enumerable
    {
        $collection = $queryBuilder->get();
        $array = $collection->toArray();
        return $this->mapperDecodeCollection($array);
    }
}
