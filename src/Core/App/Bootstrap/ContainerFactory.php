<?php

namespace Untek\Core\App\Bootstrap;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Component\Code\Helpers\DeprecateHelper;

class ContainerFactory
{

    private static ?ContainerInterface $container;

    public static function set(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function get(): ContainerInterface
    {
        return self::$container;
    }
}
