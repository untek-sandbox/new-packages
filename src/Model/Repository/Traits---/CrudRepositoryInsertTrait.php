<?php

namespace Untek\Model\Repository\Traits;

use Untek\Model\Shared\Enums\EventEnum;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\Validator\Helpers\ValidationHelper;

trait CrudRepositoryInsertTrait
{

    abstract protected function insertRaw($entity): void;

    public function create(EntityIdInterface $entity)
    {
        ValidationHelper::validateEntity($entity);
        $event = $this->dispatchEntityEvent($entity, EventEnum::BEFORE_CREATE_ENTITY);
        if ($event->isPropagationStopped()) {
            return $entity;
        }
        $this->insertRaw($entity);
        $event = $this->dispatchEntityEvent($entity, EventEnum::AFTER_CREATE_ENTITY);
    }
}
