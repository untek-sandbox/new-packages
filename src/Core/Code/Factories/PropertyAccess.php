<?php

namespace Untek\Core\Code\Factories;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;
use Untek\Core\Container\Helpers\ContainerHelper;

class PropertyAccess
{

    private static $builder;

    private function __construct()
    {
    }

    public static function createPropertyAccessor(): PropertyAccessor
    {
        return self::createPropertyAccessorBuilder()->getPropertyAccessor();
    }

    public static function createPropertyAccessorBuilder(): PropertyAccessorBuilder
    {
        if (empty(self::$builder)) {
            self::$builder = new PropertyAccessorBuilder();
            if(class_exists(AdapterInterface::class)) {
                $cacheItemPool = self::getCacheItemPool();
                self::$builder->setCacheItemPool($cacheItemPool);
            }
        }
        return self::$builder;
    }

    protected static function getCacheItemPool(): AdapterInterface
    {
        $container = ContainerHelper::getContainer();
        if ($container && $container->has(AdapterInterface::class)) {
            $cacheItemPool = $container->get(AdapterInterface::class);
        } else {
            $cacheItemPool = new ArrayAdapter();
        }
        return $cacheItemPool;
    }
}
