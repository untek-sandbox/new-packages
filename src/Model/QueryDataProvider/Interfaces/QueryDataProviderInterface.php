<?php

namespace Untek\Model\QueryDataProvider\Interfaces;

interface QueryDataProviderInterface
{

    public function countByQuery(object $query): int;

    public function findByQuery(object $query): array;
}