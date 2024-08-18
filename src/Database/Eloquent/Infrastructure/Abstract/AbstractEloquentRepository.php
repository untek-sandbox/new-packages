<?php

namespace Untek\Database\Eloquent\Infrastructure\Abstract;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use Illuminate\Database\Query\Builder;
use Untek\Component\Relation\Traits\RepositoryRelationTrait;
use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Database\Base\Domain\Traits\TableNameTrait;
use Untek\Database\Base\Hydrator\Traits\NormalizerTrait;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;

abstract class AbstractEloquentRepository implements ObjectRepository
{

    use RepositoryRelationTrait;
    use EloquentTrait;
    use TableNameTrait;
    use NormalizerTrait;

    private Connection $connection;

    abstract public function getTableName(): string;

    public function __construct(Manager $capsule)
    {
        $this->setCapsule($capsule);
    }

    protected function tableNameForQuery(): string
    {
        return 'c';
    }

    /**
     * @param mixed $id
     * @return object|null
     * @throws Exception
     */
    public function find(mixed $id, ?array $relations = null): ?object
    {
        return $this->findOneBy(['id' => $id], $relations);
    }

    /**
     * @return object[]
     * @throws Exception
     */
    public function findAll(): array
    {
        return $this->findBy([]);
    }

    /**
     * @param array $criteria
     * @return object|null
     * @throws Exception
     */
    public function findOneBy(array $criteria, ?array $relations = null): ?object
    {
        $collection = $this->findBy($criteria, null, 1, null, $relations);
        return $collection[0] ?? null;
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     * @throws Exception
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $relations = null): array
    {
        $queryBuilder = $this->makeFindQueryBuilder($criteria, $orderBy, $limit, $offset);
        $list = $this->executeFindQuery($queryBuilder);

        if ($relations) {
            $this->loadRelations($list, $relations);
        }
        return $list;
    }

    protected function createQueryBuilder(): \Illuminate\Database\Query\Builder
    {
        return $this->getQueryBuilderByTableName($this->getTableName());
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return array
     * @throws Exception
     */
    protected function executeFindQuery(Builder $queryBuilder): array
    {
        $data = $queryBuilder->get()->toArray();
        return $this->denormalizeCollection($data);
    }

    protected function makeFindQueryBuilder(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): Builder
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select('*');
        EloquentQueryBuilderHelper::fillQueryBuilder($queryBuilder, $criteria, $orderBy, $limit, $offset);
        return $queryBuilder;
    }
}
