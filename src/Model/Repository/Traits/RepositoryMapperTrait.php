<?php

namespace Untek\Model\Repository\Traits;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\Repository\Libs\MapperEncoder;
use Untek\Component\Encoder\Encoders\ChainEncoder;

trait RepositoryMapperTrait
{

    public function mappers(): array
    {
        return [

        ];
    }

    protected function underscore(array $attributes/*, array $columnList = []*/)
    {
        $arraySnakeCase = [];
        foreach ($attributes as $name => $value) {
            $tableizeName = Inflector::underscore($name);
            $arraySnakeCase[$tableizeName] = $value;
        }
        /*if ($columnList) {
            $arraySnakeCase = ArrayHelper::extractByKeys($arraySnakeCase, $columnList);
        }*/
        return $arraySnakeCase;
    }

    protected function getMapperEncoder(array $mappers = null): MapperEncoder {
        return new MapperEncoder($mappers ?: $this->mappers());
    }

    protected function mapperEncodeEntity(object $entity): array
    {
        /*$attributes = EntityHelper::toArray($entity);
        $attributes = $this->underscore($attributes);
        $mappers = $this->mappers();
        if ($mappers) {
            $encoders = new ChainEncoder(new Collection($mappers));
            $attributes = $encoders->encode($attributes);
        }*/

        $attributes = EntityHelper::toArray($entity);
        $attributes = $this->underscore($attributes);

        $attributes = $this->getMapperEncoder()->encode($attributes);

        $columnList = $this->getColumnsForModify();
        $attributes = ArrayHelper::extractByKeys($attributes, $columnList);
        return $attributes;
    }

    protected function mapperDecodeEntity(array $array): object
    {
        /*$mappers = $this->mappers();
        if ($mappers) {
            $mappers = array_reverse($mappers);
            $encoders = new ChainEncoder(new Collection($mappers));
            $array = $encoders->decode($array);
        }*/

        $array = $this->getMapperEncoder()->decode($array);

        // todo: refactor
//        if(property_exists($this, 'container')) {
//            $container = $this->container;
//        } else {
//            $container = null;
//        }
        $entityClass = $this->getEntityClass();
        $entity = new $entityClass();
//        $entity = ClassHelper::createInstance($entityClass, [], $container);
        PropertyHelper::setAttributes($entity, $array);
        return $entity;
    }

    protected function mapperDecodeCollection(array $array): Enumerable
    {
        $collection = new Collection();
        foreach ($array as $item) {
            $entity = $this->mapperDecodeEntity((array)$item);
            $collection->add($entity);
        }
        return $collection;
    }
}
