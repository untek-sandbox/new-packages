<?php

namespace Untek\Component\Relation\Libs\Types;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectRepository;
use Untek\Component\Code\Factories\PropertyAccess;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Core\Collection\Helpers\CollectionHelper;
use Untek\Model\Shared\Interfaces\FindAllInterface;

class ManyToManyRelation extends BaseRelation implements RelationInterface
{

    public $viaRepositoryClass;
    public $viaSourceAttribute;
    public $viaTargetAttribute;

    protected function loadViaByIds(array $ids): array
    {
        $foreignRepositoryInstance = $this->container->get($this->viaRepositoryClass);
        $criteria = [
            $this->viaSourceAttribute => $ids
        ];
        return $this->loadCollection($foreignRepositoryInstance, $criteria);
    }

    protected function loadRelation(array $collection): void
    {
        $ids = CollectionHelper::getColumn($collection, $this->relationAttribute);
        $ids = array_unique($ids);
        $viaCollection = $this->loadViaByIds($ids);
        $targetIds = CollectionHelper::getColumn($viaCollection, $this->viaTargetAttribute);
        $targetIds = array_unique($targetIds);
        $foreignCollection = $this->loadRelationByIds($targetIds);
        $foreignCollection = CollectionHelper::indexing($foreignCollection, 'id');
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $indexedCollection = CollectionHelper::indexing($collection, 'id');

        $result = [];
        foreach ($viaCollection as $viaEntity) {
            $targetRelationIndex = $propertyAccessor->getValue($viaEntity, $this->viaTargetAttribute);
            $sourceIndex = $propertyAccessor->getValue($viaEntity, $this->viaSourceAttribute);
            $sourceEntity = $indexedCollection[$sourceIndex];
            $targetRelationEntity = $foreignCollection[$targetRelationIndex];
            $result[$sourceIndex][] = $targetRelationEntity;
        }
        foreach ($collection as $entity) {
            $sourceIndex = $propertyAccessor->getValue($entity, 'id');
            if (isset($result[$sourceIndex])) {
                $value = $result[$sourceIndex];
                $value = $this->getValueFromPath($value);
                $propertyAccessor->setValue($entity, $this->relationEntityAttribute, new ArrayCollection($value));
            }
        }
    }

    protected function loadCollection(ObjectRepository $foreignRepositoryInstance, array $criteria): array
    {
        //count($ids)
        $collection = $foreignRepositoryInstance->findBy($criteria, null, null, null, $this->relations);
        return $collection;
    }
}
