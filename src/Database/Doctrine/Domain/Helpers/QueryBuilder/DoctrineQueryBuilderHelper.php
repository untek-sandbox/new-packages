<?php

namespace Untek\Database\Doctrine\Domain\Helpers\QueryBuilder;

use Illuminate\Database\Query\Builder;
use Yiisoft\Strings\Inflector;
use Untek\Database\Base\Domain\Helpers\DbHelper;
use Untek\Database\Base\Domain\Interfaces\QueryBuilderInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineQueryBuilderHelper implements QueryBuilderInterface
{

    public static function fillQueryBuilder(QueryBuilder $queryBuilder, array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): void
    {
        if ($orderBy) {
            DoctrineQueryBuilderHelper::addOrder($orderBy, $queryBuilder);
        }
        if ($offset) {
            $queryBuilder->setFirstResult($offset);
        }
        if ($limit) {
            $queryBuilder->setMaxResults($limit);
        }
        if ($criteria) {
            DoctrineQueryBuilderHelper::addWhere($criteria, $queryBuilder);
        }
    }

    public static function addOrder(array $orderBy, QueryBuilder $queryBuilder): void
    {
        foreach ($orderBy as $column => $direction) {
            $directionName = $direction == SORT_DESC ? 'desc' : 'asc';
            $queryBuilder->addOrderBy($column, $directionName);
        }
    }

    public static function addWhere(array $criteria, QueryBuilder $queryBuilder): void
    {
        foreach ($criteria as $key => $value) {
            $key = (new Inflector())->pascalCaseToId($key, '_');
            if (is_array($value)) {
                $expression = $queryBuilder->expr()->in($key, self::fixArrayStringExpression($value));
            } else {
                $expression = $queryBuilder->expr()->eq($key, self::fixStringExpression($value));
            }
            $predicates = $queryBuilder->expr()->andX();
            $predicates->add($expression);
            $queryBuilder->where($predicates);
        }
    }

    protected static function fixArrayStringExpression(array $values): array {
        $newValue = [];
        foreach ($values as $item) {
            $newValue[] = self::fixStringExpression($item);
        }
        return $newValue;
    }

    protected static function fixStringExpression(mixed $value): mixed {
        if(is_string($value)) {
            $value = "'$value'";
        }
        return $value;
    }

    public static function setWhere(Query $query, QueryBuilder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if ( ! empty($queryArr[Query::WHERE])) {
            foreach ($queryArr[Query::WHERE] as $key => $value) {

                $predicates = $queryBuilder->expr()->andX();

                if (is_array($value)) {
                    $predicates->add($queryBuilder->expr()->eq(/*'c.' . */$key, $value));
                    //$queryBuilder->whereIn($key, $value);
                } else {
                    $predicates->add($queryBuilder->expr()->in(/*'c.' . */$key, $value));
                    //$queryBuilder->where($key, $value);
                }
                $queryBuilder->where($predicates);
            }
        }

        $whereArray = $query->getWhereNew();
        if ( ! empty($whereArray)) {
            /** @var Where $where */
            foreach ($whereArray as $where) {

                $expr = $queryBuilder->expr();

                if($where->boolean == 'and') {
                    $predicates = $queryBuilder->expr()->andX();
                } elseif($where->boolean == 'or') {
                    $predicates = $queryBuilder->expr()->orX();
                }

                if (is_array($where->value)) {
                    $predicates->add($expr->in(/*'c.' .*/ $where->column, $where->value));
                    //$queryBuilder->whereIn($where->column, $where->value, $where->boolean, $where->not);
                } else {
                    if($where->operator == '=') {
                        if($where->not) {
                            $predicates->add($expr->eq(/*'c.' .*/ $where->column, $where->value));
                        } else {
                            $predicates->add($expr->neq(/*'c.' .*/ $where->column, $where->value));
                        }
                    } elseif ($where->operator == '<>') {
                        if($where->not) {
                            $predicates->add($expr->neq(/*'c.' .*/ $where->column, $where->value));
                        } else {
                            $predicates->add($expr->eq(/*'c.' .*/ $where->column, $where->value));
                        }
                    } elseif ($where->operator == 'NULL') {
                        if($where->not) {
                            $predicates->add($expr->isNotNull($where->column));
                        } else {
                            $predicates->add($expr->isNull($where->column));
                        }
                    } elseif ($where->operator == 'NOT NULL') {
                        if($where->not) {
                            $predicates->add($expr->isNull($where->column));
                        } else {
                            $predicates->add($expr->isNotNull($where->column));
                        }
                    } elseif ($where->operator == '<') {
                        $predicates->add($expr->lt($where->column, $where->value));
                    } elseif ($where->operator == '<=') {
                        $predicates->add($expr->lte($where->column, $where->value));
                    } elseif ($where->operator == '>') {
                        $predicates->add($expr->gt($where->column, $where->value));
                    } elseif ($where->operator == '>=') {
                        $predicates->add($expr->gte($where->column, $where->value));
                    } elseif ($where->operator == 'like') {
                        if($where->not) {
                            $predicates->add($expr->notLike($where->column, $where->value));
                        } else {
                            $predicates->add($expr->like($where->column, $where->value));
                        }
                    }

                    //$queryBuilder->where($where->column, $where->operator, $where->value, $where->boolean);
                }

                $queryBuilder->where($predicates);
            }
        }
    }

    public static function setOrder(Query $query, QueryBuilder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if ( ! empty($queryArr[Query::ORDER])) {
            foreach ($queryArr[Query::ORDER] as $field => $direction) {
                $queryBuilder->orderBy($field, DbHelper::encodeDirection($direction));
            }
        }
    }

    public static function setSelect(Query $query, QueryBuilder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if ( ! empty($queryArr[Query::SELECT])) {
            $queryBuilder->select($queryArr[Query::SELECT]);
        }
    }

    public static function setPaginate(Query $query, QueryBuilder $queryBuilder)
    {
        $queryArr = $query->toArray();
        if ( ! empty($queryArr[Query::LIMIT])) {
            $queryBuilder->setMaxResults($queryArr[Query::LIMIT]);
        }
        if ( ! empty($queryArr[Query::OFFSET])) {
            $queryBuilder->setFirstResult($queryArr[Query::OFFSET]);
        }
    }

}