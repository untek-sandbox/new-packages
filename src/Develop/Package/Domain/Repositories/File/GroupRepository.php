<?php

namespace Untek\Develop\Package\Domain\Repositories\File;

use Untek\Component\FormatAdapter\StoreFile;
use Untek\Develop\Package\Domain\Entities\GroupEntity;
use Untek\Model\Entity\Interfaces\EntityIdInterface;
use Untek\Model\Repository\Interfaces\ReadRepositoryInterface;
use Untek\Persistence\Normalizer\Traits\NormalizerTrait;

class GroupRepository //implements ReadRepositoryInterface
{

    use NormalizerTrait;

    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function findAll(Query $query = null): array
    {
        $store = new StoreFile($this->fileName);
        $array = $store->load();

        return $this->denormalizeCollection($array);

        //$collection = $this->forgeEntityCollection($array);
        //return $collection;
//        $entityClass = $this->getEntityClass();
//        return CollectionHelper::create($entityClass, $array);
    }

    public function count(Query $query = null): int
    {
        $collection = $this->findAll($query);
        return $collection->count();
    }

    public function findOneById($id, Query $query = null): EntityIdInterface
    {
        // TODO: Implement findOneById() method.
    }

    public function getEntityClass(): string
    {
        return GroupEntity::class;
    }

    public function getClassName(): string
    {
        return GroupEntity::class;
    }



    /*public function _relations()
    {
        return [];
    }*/

}
