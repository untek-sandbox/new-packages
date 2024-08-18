<?php

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\FrameworkPlugin\HttpAuthentication\Infrastructure\Subscribers\WebAuthenticationSubscriber;

\Untek\Component\Code\Helpers\DeprecateHelper::hardThrow();

return function (EventDispatcherInterface $eventDispatcher, ContainerInterface $container) {
    $webAuthenticationSubscriber = $container->get(WebAuthenticationSubscriber::class);
    $eventDispatcher->addSubscriber($webAuthenticationSubscriber);
};
