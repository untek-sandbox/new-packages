<?php

namespace Untek\Component\Enum\Helpers;

use ReflectionClass;

class ReflectionHelper
{

    public static function getConstantsByPrefix($class, $prefix)
    {
        $constants = self::getConstants($class);
        return self::filterByPrefix($constants, $prefix);
    }

    public static function getConstants($class)
    {
        return (new ReflectionClass($class))->getConstants();
    }

    private static function filterByPrefix($constants, $prefix)
    {
        $ucPrefix = strtoupper($prefix);
        $result = [];
        foreach ($constants as $name => $value) {
            if (str_starts_with($name, $ucPrefix)) {
                $result[$name] = $value;
            }
        }
        return $result;
    }
}
