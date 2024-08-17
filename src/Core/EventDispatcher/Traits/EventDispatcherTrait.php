<?php

namespace Untek\Core\EventDispatcher\Traits;

use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Untek\Core\Instance\Helpers\ClassHelper;

//\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

trait EventDispatcherTrait
{

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function subscribes(): array
    {
        return [];
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    protected function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->initEventDispatcher();
    }

    /**
     * @return EventDispatcherInterface | \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        if (!isset($this->eventDispatcher)) {
            $eventDispatcher = new EventDispatcher();
            $this->setEventDispatcher($eventDispatcher);
        }
        return $this->eventDispatcher;
    }

    private function initEventDispatcher(): void
    {
        foreach ($this->subscribes() as $subscriberDefinition) {
            $subscriberInstance = $this->forgeSubscriberInstance($subscriberDefinition);
            $this->eventDispatcher->addSubscriber($subscriberInstance);
        }
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0)
    {
//        $listenerInstance = $this->forgeSubscriberInstance($listenerDefinition);
//        $this->getEventDispatcher()->addListener($callback, $listenerInstance);
        $this->getEventDispatcher()->addListener($eventName, $listener, $priority);
    }

    private function forgeSubscriberInstance($subscriberDefinition): EventSubscriberInterface
    {
        if ($subscriberDefinition instanceof EventSubscriberInterface) {
            $subscriberInstance = $subscriberDefinition;
        } else {
            $subscriberInstance = ClassHelper::createObject($subscriberDefinition);
        }
        return $subscriberInstance;
    }
}
