<?php

namespace Untek\Persistence\Contract\Interfaces;

interface RepositoryCreateInterface
{

    /**
     * @param object $entity
     */
    public function create(object $entity): void;
}