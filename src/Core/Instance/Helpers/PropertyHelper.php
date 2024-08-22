<?php

namespace Untek\Core\Instance\Helpers;

use Untek\Component\Arr\Helpers\ArrayHelper;
use Untek\Lib\Components\DynamicEntity\Interfaces\DynamicEntityAttributesInterface;
use Throwable;
use Yiisoft\Strings\Inflector;
use Untek\Component\Code\Factories\PropertyAccess;

/**
 * Работа с атрибутами класса
 */
class PropertyHelper
{

    public static function mergeObjects(object $sourceObject, object $targetObject): void
    {
        $sourceData = PropertyHelper::toArray($sourceObject);
        PropertyHelper::setAttributes($targetObject, $sourceData);
    }

    public static function createObject($className, array $attributes = []): object
    {
        $entityInstance = ClassHelper::createObject($className);
        if ($attributes) {
            \Untek\Core\Instance\Helpers\PropertyHelper::setAttributes($entityInstance, $attributes);
        }
        return $entityInstance;
    }

    public static function toArray($entity, bool $recursive = false): array
    {
        $array = [];
        if (is_array($entity)) {
            $array = $entity;
        } elseif ($entity instanceof \Doctrine\Common\Collections\Collection) {
            $array = $entity->toArray();
        } elseif (is_object($entity)) {
            $attributes = self::getAttributeNames($entity);
            if ($attributes) {
//                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                foreach ($attributes as $attribute) {
                    $array[$attribute] = self::getValue($entity, $attribute);
//                    $array[$attribute] = $propertyAccessor->getValue($entity, $attribute);
                }
            } else {
                $array = (array)$entity;
            }
        }
        if ($recursive) {
            foreach ($array as $key => $item) {
                if (is_object($item) || is_array($item)) {
                    $array[$key] = self::toArray($item, $recursive);
                }
            }
        }
        foreach ($array as $key => $value) {
            $isPrivate = mb_strpos($key, "\x00*\x00") !== false;
            if ($isPrivate) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public static function getAttributeNames($entity): array
    {
        $reflClass = new \ReflectionClass($entity);
        $attributesRef = $reflClass->getProperties();
        $attributes = ArrayHelper::getColumn($attributesRef, 'name');
        foreach ($attributes as $index => $attributeName) {
            if ($attributeName[0] == '_') {
                unset($attributes[$index]);
            }
        }
        return $attributes;
    }

    /**
     * Получить значение атрибута.
     *
     * @param object $entity
     * @param string $attribute
     * @param mixed | null $defaultValue
     * @return mixed
     */
    public static function getValue(object $entity, string $attribute, mixed $defaultValue = null): mixed
    {
        if(is_array($entity)) {
            return ArrayHelper::getValue($entity, $attribute);
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        try {
            return $propertyAccessor->getValue($entity, $attribute);
        } catch (Throwable $e) {
            // todo: логировать ошибки доступа к атрибутам
            return $defaultValue;
        }
    }

    /**
     * Установить значение атрибута.
     *
     * @param object $entity
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public static function setValue(object $entity, string $name, mixed $value): void
    {
        if(is_array($entity)) {
            ArrayHelper::set($entity, $name, $value);
            return;
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $propertyAccessor->setValue($entity, $name, $value);
    }

    /**
     * Назначить массив атрибутов.
     *
     * @param object $entity
     * @param object | array $data
     * @param array $filedsOnly
     */
    public static function setAttributes(object $entity, object | array $data, array $filedsOnly = []): void
    {
        if (empty($data)) {
            return;
        }

        if(is_array($entity)) {
            $data = ArrayHelper::only($data);
            $entity = ArrayHelper::merge($entity, $data);
            return;
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($data as $name => $value) {
            $name = (new Inflector())->toCamelCase($name);
            $isAllow = empty($filedsOnly) || in_array($name, $filedsOnly);
            if ($isAllow) {
                $isWritable = $propertyAccessor->isWritable($entity, $name);
                if ($isWritable) {
                    $propertyAccessor->setValue($entity, $name, $value);
                }
            }
        }
    }

    /**
     * Проверяет, доступен ли атрибут для записи.
     *
     * @param object $entity
     * @param string $name
     * @return bool
     */
    public static function isWritableAttribute(object $entity, string $name): bool
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        return $propertyAccessor->isWritable($entity, $name);
    }

    /**
     * Проверяет, доступен ли атрибут для чтения.
     *
     * @param object $entity
     * @param string $name
     * @return bool
     */
    public static function isReadableAttribute(object $entity, string $name): bool
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        return $propertyAccessor->isReadable($entity, $name);
    }
}
