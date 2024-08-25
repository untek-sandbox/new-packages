<?php

namespace Untek\Database\Eloquent\Domain\Capsule;

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Database\Base\Domain\Libs\TableAlias;

DeprecateHelper::hardThrow();

class Manager extends CapsuleManager
{

//    private TableAlias $tableAlias;
//    private array $connectionMap = [];

//    public function getTableAlias(): TableAlias
//    {
//        DeprecateHelper::hardThrow();
//        return $this->tableAlias;
//    }
//
//    public function setTableAlias(TableAlias $tableAlias): void
//    {
//        DeprecateHelper::hardThrow();
//        $this->tableAlias = $tableAlias;
//    }
//
//    /**
//     * @return TableAlias
//     * @deprecated
//     * @see getTableAlias
//     */
//    public function getAlias(): TableAlias
//    {
//        DeprecateHelper::hardThrow();
//        return $this->tableAlias;
//    }
//
//
//
//
//    public function getSchemaByConnectionName($connectionName): SchemaBuilder
//    {
//        DeprecateHelper::hardThrow();
//        //$connection = $this->getConnectionByTableName($tableName);
//        $connection = $this->getConnection($connectionName);
//        return $connection->getSchemaBuilder();
//    }
//
//    public function getQueryBuilderByConnectionName($connectionName, string $tableNameAlias): QueryBuilder
//    {
//        DeprecateHelper::hardThrow();
//        $connection = $this->getConnection($connectionName);
//        return $connection->table($tableNameAlias, null);
//    }
//
//    public function getConnectionByTableName(string $tableName): Connection
//    {
//        DeprecateHelper::hardThrow();
//        $connectionName = $this->getConnectionNameByTableName($tableName);
//        return $this->getConnection($connectionName);
//    }
//
//    public function getSchemaByTableName($tableName): SchemaBuilder
//    {
//        DeprecateHelper::hardThrow();
//        $connection = $this->getConnectionByTableName($tableName);
//        return $connection->getSchemaBuilder();
//    }
//
//    public function getConnectionNames(): array
//    {
//        DeprecateHelper::hardThrow();
//        $connections = array_values($this->getConnectionMap());
//        $connections[] = 'default';
//        $connections = array_unique($connections);
//        $connections = array_values($connections);
//        return $connections;
//    }
//
//    public function isInOneDatabase(string $tableName1, string $tableName2): bool
//    {
//        DeprecateHelper::hardThrow();
//        return ExtArrayHelper::getValue($this->connectionMap, $tableName1, 'default') == ExtArrayHelper::getValue($this->connectionMap, $tableName2, 'default');
//    }
//
//    public function getConnectionNameByTableName(string $tableName)
//    {
//        DeprecateHelper::hardThrow();
//        return ExtArrayHelper::getValue($this->connectionMap, $tableName, 'default');
//    }
//
//    public function getConnectionMap(): array
//    {
//        DeprecateHelper::hardThrow();
//        return $this->connectionMap;
//    }
//
//    public function setConnectionMap($connectionMap): void
//    {
////        DeprecateHelper::hardThrow();
//        $this->connectionMap = $connectionMap;
//    }
}
