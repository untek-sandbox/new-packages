<?php

namespace Untek\Core\Container\Helpers;

use Psr\Container\ContainerInterface;
use Untek\Core\Container\Libs\ContainerWrapper;
use Untek\Core\Contract\Common\Exceptions\ReadOnlyException;
use Untek\Develop\DebugBacktrace\DebugBacktrace;

class ContainerHelper
{

    /*private static ?ContainerInterface $container = null;

    public static function setContainer(ContainerInterface $container): void
    {
        if (self::$container) {
//            throw new ReadOnlyException();
        }
        self::$container = $container;
    }*/

    public static function getContainer(): ContainerInterface
    {
//        DebugBacktrace::dump();
//        return null;
        return ContainerWrapper::getContainer();
//        return self::$container;
    }
}
