<?php

namespace Untek\Core\Instance\Helpers;

use ReflectionException;
use ReflectionParameter;
use Untek\Component\Text\Helpers\Inflector;

class MappingHelper
{

    public static function restoreObject(array $data, string $className): object
    {
        $object = new $className();
        foreach ($data as $fieldName => $fieldValue) {
            if (!is_scalar($fieldValue)) {
                try {
                    $methodName = 'set' . Inflector::camelize($fieldName);
                    $param = new ReflectionParameter([$object, $methodName], 0);
                    $fieldClassName = $param->getType()->getName();
                    if(class_exists($fieldClassName)) {
                        $fieldValue = self::restoreObject($fieldValue, $fieldClassName);
                    }
                } catch (ReflectionException $e) {
                    $fieldValue = null;
                }
            }
            PropertyHelper::setValue($object, $fieldName, $fieldValue);
        }
        return $object;
    }
}