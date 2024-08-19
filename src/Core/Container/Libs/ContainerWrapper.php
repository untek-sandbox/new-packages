<?php

namespace Untek\Core\Container\Libs;

use Psr\Container\ContainerInterface;

class ContainerWrapper implements ContainerInterface {
    
    static $globalContainer;
    
    public function __construct(ContainerInterface $container)
    {
        self::$globalContainer = $container;
    }

    public function get(string $id): mixed
    {
        return self::$globalContainer->get($id);
    }

    public function has(string $id): bool
    {
        return self::$globalContainer->has($id);
    }

    public static function getContainer(): ?ContainerInterface
    {
        return self::$globalContainer;
    }
}
