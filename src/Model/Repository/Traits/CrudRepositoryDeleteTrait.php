<?php

namespace Untek\Model\Repository\Traits;

use Untek\Model\Shared\Enums\EventEnum;

trait CrudRepositoryDeleteTrait
{

    abstract protected function deleteByIdQuery($id): void;

    public function deleteById($id)
    {
        $entity = $this->findOneById($id);
        $event = $this->dispatchEntityEvent($entity, EventEnum::BEFORE_DELETE_ENTITY);
        if (!$event->isSkipHandle()) {
            $this->deleteByIdQuery($id);
        }
        $event = $this->dispatchEntityEvent($entity, EventEnum::AFTER_DELETE_ENTITY);
    }
}
