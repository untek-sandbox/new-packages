<?php

namespace Untek\Database\Base\Domain\Repositories\Sqlite;

use App\Example\Controllers\ExampleEntity;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\MySqlBuilder;
use Illuminate\Database\Schema\PostgresBuilder;
use Untek\Component\Arr\Helpers\ArrayHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Database\Base\Domain\Entities\ColumnEntity;
use Untek\Database\Base\Domain\Entities\RelationEntity;
use Untek\Database\Base\Domain\Entities\TableEntity;
use Illuminate\Database\Capsule\Manager;
use Untek\Database\Eloquent\Domain\Traits\EloquentTrait;
use Untek\Database\Fixture\Domain\Helpers\StructHelper;

class DbRepository
{

    use EloquentTrait;

//    private $capsule;

    public function __construct(Manager $capsule)
    {
        $this->setCapsule($capsule);
    }

    /*public function connectionName()
    {
        return 'default';
    }*/

    /*public function getConnection(): Connection
    {
        $connection = $this->capsule->getConnection($this->connectionName());
        return $connection;
    }

    protected function getSchema(): SchemaBuilder
    {
        $connection = $this->getConnection();
        $schema = $connection->getSchemaBuilder();
        return $schema;
    }

    public function getCapsule(): Manager
    {
        return $this->capsule;
    }

    public function getEntityClass(): string
    {
        return FixtureEntity::class;
    }*/

    public static function allPostgresTables(ConnectionInterface $connection): Enumerable
    {
        $schemaCollection = StructHelper::allPostgresSchemas($connection);
        $tableCollection = new Collection();
        foreach ($schemaCollection as $schemaEntity) {
            $tables = $connection->select("SELECT * FROM information_schema.tables WHERE table_schema = '{$schemaEntity->getName()}'");
            // select * from pg_tables where schemaname='public';
            $tableNames = ArrayHelper::getColumn($tables, 'table_name');
            foreach ($tableNames as $tableName) {
                $tableEntity = new \Untek\Database\Base\Domain\Entities\TableEntity;
                $tableEntity->setName($tableName);
                $tableEntity->setSchema($schemaEntity);
                $tableCollection->add($tableEntity);
            }
        }
        return $tableCollection;
    }

    public function allRelations(string $tableName): Enumerable
    {
        $sql = "SELECT
tc.constraint_name, tc.table_name, kcu.column_name, 
ccu.table_name AS foreign_table_name,
ccu.column_name AS foreign_column_name 
FROM 
information_schema.table_constraints AS tc 
JOIN information_schema.key_column_usage AS kcu
  ON tc.constraint_name = kcu.constraint_name
JOIN information_schema.constraint_column_usage AS ccu
  ON ccu.constraint_name = tc.constraint_name
WHERE constraint_type = 'FOREIGN KEY' AND tc.table_name='$tableName';";
        $array = $this->getConnection()->select($sql);
        $collection = new Collection();
        foreach ($array as $item) {
            $relationEntity = new RelationEntity();
            $relationEntity->setConstraintName($item->constraint_name);
            $relationEntity->setTableName($item->table_name);
            $relationEntity->setColumnName($item->column_name);
            $relationEntity->setForeignTableName($item->foreign_table_name);
            $relationEntity->setForeignColumnName($item->foreign_column_name);
            $collection->add($relationEntity);
        }
        return $collection;
    }

    public function allTablesByName(array $nameList): Enumerable
    {
        /** @var TableEntity[] $collection */
        $collection = $this->allTables();
        $newCollection = new Collection();
        foreach ($collection as $tableEntity) {
            if (in_array($tableEntity->getName(), $nameList)) {
                $columnCollection = $this->getColumnsByTable($tableEntity->getName());
                $tableEntity->setColumns($columnCollection);
                $relationCollection = $this->allRelations($tableEntity->getName());
                $tableEntity->setRelations($relationCollection);
                $newCollection->add($tableEntity);
            }
        }
        return $newCollection;
    }

    public function allTables(): Enumerable
    {
//        $tableAlias = $this->getCapsule()->getAlias();
        /* @var Builder|MySqlBuilder|PostgresBuilder $schema */
        $schema = $this->getSchema();

        $dbName = $schema->getConnection()->getDatabaseName();
        $collection = new Collection();
        /*if ($schema->getConnection()->getDriverName() == DbDriverEnum::SQLITE) {
            
        } else {
            if ($schema->getConnection()->getDriverName() == DbDriverEnum::PGSQL) {
                $tableCollection = self::allPostgresTables($schema->getConnection());
            } else {
                $tableCollection = StructHelper::allTables($schema);
            }
            foreach ($tableCollection as $tableEntity) {
                $tableName = StructHelper::getTableNameFromEntity($tableEntity);
                $sourceTableName = $tableAlias->decode('default', $tableName);
                $tableEntity1 = new TableEntity();
                $tableEntity1->setName($tableName);
                $tableEntity1->setSchemaName($tableEntity->getSchema()->getName());
                $tableEntity1->setDbName($tableEntity->getSchema()->getDbName());
                $collection->add($tableEntity1);
            }
        }*/

        $array = $schema->getConnection()->getPdo()->query('SELECT name FROM sqlite_master WHERE type=\'table\'')->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($array as $targetTableName) {
            $tableEntity1 = new TableEntity($targetTableName);
            //$tableName = StructHelper::getTableNameFromEntity($tableEntity);
            $tableEntity1->setName($targetTableName);
//            $tableEntity1->setSchemaName('public');
            $tableEntity1->setDbName($dbName);
            $collection->add($tableEntity1);
        }


        return $collection;
    }

    /**
     * @param string $tableName
     * @return Enumerable | ColumnEntity[]
     */
    public function getColumnsByTable(string $tableName): Enumerable
    {
        $schema = $this->getSchema();
        $columnList = $schema->getColumnListing($tableName);
        $columnCollection = new Collection();
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
