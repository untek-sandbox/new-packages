<?php

namespace Untek\Core\Collection\Helpers;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Untek\Component\Dev\Helpers\DeprecateHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;

/**
 * Хэлпер для работы с коллекциями.
 */
class CollectionHelper
{

    /**
     * Преобразовать коллекцию в индексированный массив.
     *
     * @param Collection $collection Исходная коллекция
     * @param string $fieldName Имя поля для индекса (должно быть уникальным)
     * @return array
     */
    public static function indexing(Collection|array $collection, string $fieldName): array
    {
        $array = [];
        foreach ($collection as $item) {
            $pkValue = PropertyHelper::getValue($item, $fieldName);
            $array[$pkValue] = $item;
        }
        return $array;
    }

    /**
     * Получить массив значений одного атрибута.
     *
     * @param Collection $collection Исходная коллекция
     * @param string $key Имя атрибута
     * @return array Массив значений атрибута
     */
    public static function getColumn(Collection|array $collection, string $key): array
    {
        $array = [];
        foreach ($collection as $entity) {
            $array[] = PropertyHelper::getValue($entity, $key);
        }
        $array = array_values($array);
        return $array;
    }





    /**
     * Фильтрация коллекции по условию.
     *
     * @param Collection $collection
     * @param string $field Имя поля
     * @param string $operator Оператор сравнения
     * @param mixed $value Значение
     * @return Collection
     */
    public static function where(Collection $collection, $field, $operator, $value)
    {
        DeprecateHelper::hardThrow();

        $expr = new Comparison($field, $operator, $value);
        $criteria = new Criteria();
        $criteria->andWhere($expr);
        return $collection->matching($criteria);
    }

    /**
     * Слияние коллекций.
     *
     * @param Collection $collection Коллекция 1
     * @param Collection $source Коллекция 2
     * @return Collection
     */
    public static function merge(Collection $collection, Collection $source): Collection
    {
        DeprecateHelper::hardThrow();

        $result = clone $collection;
        self::appendCollection($result, $source);
        return $result;
    }

    /**
     * Добавить коллекцию элементов.
     *
     * @param Collection $collection Исходная коллекция
     * @param Collection $source Добавляемая коллекция
     */
    public static function appendCollection(Collection $collection, Collection $source): void
    {
        DeprecateHelper::hardThrow();

        foreach ($source as $item) {
            $collection->add($item);
        }
    }

    /**
     * Разделить коллекцию на куски.
     *
     * @param Collection $collection Исходная коллекция
     * @param int $size Размер куска
     * @return Collection Коллекция коллекций
     */
    public static function chunk(Collection $collection, int $size): Collection
    {
        DeprecateHelper::hardThrow();

        if ($size <= 0) {
            return new ArrayCollection();
        }
        $chunks = [];
        foreach (array_chunk($collection->toArray(), $size, true) as $chunk) {
            $chunks[] = new ArrayCollection($chunk);
        }
        return new ArrayCollection($chunks);
    }

    /**
     * Создать коллекцию сущностей.
     *
     * @param string $entityClass Имя класса сущности
     * @param array $data Массив значений атрибутов сущности
     * @param array $filedsOnly Назначать только указанные атрибуты
     * @return Collection
     */
    public static function create(string $entityClass, array $data = [], array $filedsOnly = []): Collection
    {
        DeprecateHelper::hardThrow();

        $data = self::createEntityArray($entityClass, $data, $filedsOnly);
        $collection = new ArrayCollection($data);
        return $collection;
    }

    public static function createEntityArray(string $entityClass, array $data = [], array $filedsOnly = []): array
    {
        DeprecateHelper::hardThrow();

        foreach ($data as $key => $item) {
            $entity = new $entityClass;
            PropertyHelper::setAttributes($entity, $item, $filedsOnly);
            $data[$key] = $entity;
        }
        return $data;
    }

    /**
     * Преобразовать коллекцию в массив.
     *
     * @param Collection $collection Исходная коллекция
     * @return array
     */
    public static function toArray(Collection $collection): array
    {
        DeprecateHelper::hardThrow();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $normalizeHandler = function ($value) use ($serializer) {
            return $serializer->normalize($value);
            //return is_object($value) ? EntityHelper::toArray($value) : $value;
        };
        $normalizeCollection = $collection->map($normalizeHandler);
        return $normalizeCollection->toArray();
    }
}
