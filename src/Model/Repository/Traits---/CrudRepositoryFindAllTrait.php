<?php

namespace Untek\Model\Repository\Traits;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Query\Entities\Query;

trait CrudRepositoryFindAllTrait
{

    public function findAll(Query $query = null): Enumerable
    {
        $query = $this->forgeQuery($query);
        $collection = $this->findBy($query);
        $this->loadRelationsByQuery($collection, $query);
        return $collection;
    }
}
