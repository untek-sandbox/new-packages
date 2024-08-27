<?php

namespace Untek\Component\Code\Helpers;

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
        $result = [];
        foreach ($constants as $name => $value) {
            if (self::isNameWithPrefix($prefix, $name)) {
                $result[$name] = $value;
            }
        }
        return $result;
    }

    private static function isNameWithPrefix($prefix, $name)
    {
        $ucPrefix = strtoupper($prefix);
        $ucPrefixWithBl = $ucPrefix . '_';
        return strpos($name, $ucPrefixWithBl) === 0;
    }

    /*public static function getConstantsValuesByPrefix($class, $prefix)
    {
        $constants = self::getConstantsByPrefix($class, $prefix);
        return array_values($constants);
    }

    public static function getConstantsNamesByPrefix($class, $prefix)
    {
        $constants = self::getConstantsByPrefix($class, $prefix);
        return array_keys($constants);
    }

    public static function getProperties($class)
    {
        $class = new ReflectionClass($class);
        $properties = $class->getProperties();
        return $properties;
    }*/
}
