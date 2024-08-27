<?php

namespace Untek\Framework\WebSocketTest\Asserts;

namespace Untek\Framework\WebSocketTest\Asserts;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Collection;
use Untek\Component\Dev\Helpers\DeprecateHelper;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;

DeprecateHelper::hardThrow();

class WebSocketReceiver
{

    public function __construct(protected Manager $manager)
    {
    }

    public function findByUser(int $userId): Collection
    {
        return $this->getAll([
            'to_user_id' => $userId,
        ]);
    }

    protected function getAll(array $condition): Collection
    {
        $queryBuilder = $this->manager
            ->getConnection()
            ->table('websocket_message');
        EloquentQueryBuilderHelper::setWhere($condition, $queryBuilder);
        $collection = $queryBuilder->get();
        return $collection;
    }
}