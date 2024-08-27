<?php

namespace Untek\Model\QueryDataProvider\Interfaces;

interface DataProviderWithQueryInterface
{

    public function countByQuery(object $query): int;

    public function findByQuery(object $query): array;
}