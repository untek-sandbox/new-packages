<?php

namespace Untek\Framework\WebSocketTest\Asserts;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Untek\Database\Eloquent\Infrastructure\Helpers\QueryBuilder\EloquentQueryBuilderHelper;

class WebSocketAssert extends Assert
{

    public function __construct(private Manager $manager)
    {

    }

    public function assertHasMessage(int $userId, array $payload, ?int $fromUserId = null): self {
        $has = $this->hasMessage($userId, $payload, $fromUserId);
        if(!$has) {
            throw new ExpectationFailedException('Web socket message not found.');
        }
        return $this;
    }

    protected function hasMessage(int $userId, array $payload, ?int $fromUserId = null): bool {
        $all = $this->findByUser($userId);
        $has = true;
        foreach ($all as $item) {
            $item = (array) $item;
            $itemPayload = json_decode($item['payload'], true);
            if($itemPayload != $payload) {
                $has = false;
            }
            if($fromUserId && $fromUserId != $item['from_user_id']) {
                $has = false;
            }
        }
        return $has;
    }

    protected function findByUser(int $userId): Collection
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
