<?php

namespace Untek\Database\Base\Domain\Repositories\Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Illuminate\Database\Capsule\Manager;
use Untek\Database\Base\Domain\Entities\ColumnEntity;
use Untek\Model\Shared\Helpers\EntityHelper;

abstract class DbRepository
{

//    use EloquentTrait;

//    private $capsule;
    protected Manager $capsule;

    public function __construct(Manager $capsule)
    {
        $this->capsule = $capsule;
//        $this->setCapsule($capsule);
    }

    public function getCapsule(): Manager
    {
        return $this->capsule;
    }

    /*public function connectionName()
    {
        return 'default';
    }*/

    /*public function getConnection(): Connection
    {
        $connection = $this->capsule->getConnection($this->connectionName());
        return $connection;
    }*/

    /*protected function getSchema(): SchemaBuilder
    {
        $connection = $this->getConnection();
        $schema = $connection->getSchemaBuilder();
        return $schema;
    }*/

    /*public function getCapsule(): Manager
    {
        return $this->capsule;
    }*/

    /**
     * @param string $tableName
     * @param string $schemaName
     * @return Collection | ColumnEntity[]
     */
    public function allColumnsByTable(string $tableName, string $schemaName = 'public'): Collection
    {
        $connection = $this
            ->getCapsule()
            ->getConnection();
        $schema = $connection->getSchemaBuilder();
        $columnList = $schema->getColumnListing($tableName);
        $columnCollection = new ArrayCollection();
        foreach ($columnList as $columnName) {
            $columnType = $schema->getColumnType($tableName, $columnName);
            $columnEntity = new ColumnEntity();
            $columnEntity->setName($columnName);
            $columnEntity->setType($columnType);
            $columnCollection->add($columnEntity);
        }
        return $columnCollection;
    }

}
