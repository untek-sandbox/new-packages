<?php

namespace Untek\Component\Code\Factories;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;

class PropertyAccess
{

    private static PropertyAccessorBuilder $builder;
    private static AdapterInterface $adapter;
    private static PropertyAccessor $accessor;

    public static function createPropertyAccessor(): PropertyAccessor
    {
        if (!isset(self::$accessor)) {
            self::$accessor = self::createPropertyAccessorBuilder()->getPropertyAccessor();
        }
        return self::$accessor;
    }

    protected static function createPropertyAccessorBuilder(): PropertyAccessorBuilder
    {
        $builder = new PropertyAccessorBuilder();
        if (class_exists(AdapterInterface::class)) {
            $cacheItemPool = self::getCacheItemPool();
            $builder->setCacheItemPool($cacheItemPool);
        }
        return $builder;
    }

    protected static function getCacheItemPool(): AdapterInterface
    {
        return new ArrayAdapter();

        /*$container = ContainerHelper::getContainer();
        if ($container && $container->has(AdapterInterface::class)) {
            $cacheItemPool = $container->get(AdapterInterface::class);
        } else {
            $cacheItemPool = new ArrayAdapter();
        }
        return $cacheItemPool;*/
    }
}
