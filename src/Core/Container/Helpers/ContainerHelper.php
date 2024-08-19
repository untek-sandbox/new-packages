<?php

namespace Untek\Core\Container\Helpers;

use Psr\Container\ContainerInterface;
use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Core\Contract\Common\Exceptions\ReadOnlyException;

DeprecateHelper::hardThrow();

class ContainerHelper
{

    private static ?ContainerInterface $container = null;

    public static function setContainer(ContainerInterface $container): void
    {
        if (self::$container) {
//            throw new ReadOnlyException();
        }
        self::$container = $container;
    }

    public static function getContainer(): ?ContainerInterface
    {
        return self::$container;
    }
}
