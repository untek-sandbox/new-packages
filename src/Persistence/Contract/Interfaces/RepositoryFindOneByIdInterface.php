<?php

namespace Untek\Persistence\Contract\Interfaces;

use Untek\Persistence\Contract\Exceptions\NotFoundException;

interface RepositoryFindOneByIdInterface
{

    /**
     * @param int $id
     * @param array|null $relations
     * @return object
     * @throws NotFoundException
     */
    public function findOneById(mixed $id, ?array $relations = null): object;
}