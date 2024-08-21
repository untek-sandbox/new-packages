<?php

namespace Untek\Persistence\Contract\Interfaces;

use Untek\Persistence\Contract\Exceptions\NotFoundException;

interface RepositoryDeleteByIdInterface
{

    /**
     * @param int $id
     * @throws NotFoundException
     */
    public function deleteById(mixed $id): void;
}