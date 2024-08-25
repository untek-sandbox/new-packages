<?php

namespace Untek\Component\Arr\Helpers;

use Closure;
use Yiisoft\Arrays\ArrayHelper;

/**
 * Хэлпер для работы с массивами.
 */
class ExtArrayHelper
{

    /**
     * Удалить пустые элементы массива.
     *
     * @param array $data
     * @return array
     */
    public static function removeEmptyItems(array $data): array
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * Получить элементы массива, содержащие указанные поля.
     *
     * @param array $array
     * @param array $keys
     * @return array
     */
    public static function extractItemsWithAttributes(array $array, array $keys = []): array
    {
        foreach ($array as &$item) {
            $item = self::extractByKeys($item, $keys);
        }
        return $array;
    }

    /**
     * Сортировка массива по длине строки.
     *
     * @param $a
     * @param $b
     * @return int
     */
    public static function sortByLen($a, $b)
    {
        if (strlen($a) < strlen($b)) {
            return 1;
        } elseif (strlen($a) == strlen($b)) {
            return 0;
        } else {
            return -1;
        }
    }

    /**
     * Получить ключ первого элемента массива
     *
     * @param array $array
     * @return mixed
     */
    public static function firstKey(array $array)
    {
        $keys = array_keys($array);
        $firstKey = $keys[0];
        return $firstKey;
    }

    public static function extractByKeys($array, $keys)
    {
        if (empty($keys)) {
            return $array;
        }
        if (is_object($array)) {
            $array = ArrayHelper::toArray($array);
        }
        $result = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }
        return $result;
    }

    public static function removeByValue($value, &$array)
    {
        $key = array_search($value, $array);
        if ($key !== FALSE) {
            unset($array[$key]);
        }
    }

    public static function recursiveIterator(array $array, $callback)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::recursiveIterator($value, $callback);
            } else {
                $array[$key] = call_user_func($callback, $value);//$callback($value);
            }
        }
        return $array;
    }

    // ==========

    /**
     * Checks whether a variable is an array or [[\Traversable]].
     *
     * This method does the same as the PHP function [is_array()](https://secure.php.net/manual/en/function.is-array.php)
     * but additionally works on objects that implement the [[\Traversable]] interface.
     * @param mixed $var The variable being evaluated.
     * @return bool whether $var is array-like
     * @see https://secure.php.net/manual/en/function.is-array.php
     * @since 2.0.8
     */
    public static function isTraversable($var)
    {
        return is_array($var) || $var instanceof \Traversable;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
