<?php

namespace Untek\Database\Eloquent\Domain\Factories;

use Illuminate\Database\Capsule\Manager;
use Untek\Component\Arr\Helpers\ArrayPathHelper;
use Untek\Component\FileSystem\Helpers\FileStorageHelper;
use Untek\Component\FormatAdapter\Helpers\StoreHelper;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;
use Untek\Database\Base\Domain\Facades\DbFacade;
use Untek\Database\Base\Domain\Libs\TableAlias;

class ManagerFactory
{

    public static function createManagerFromEnv(): Manager
    {
        $connections = DbFacade::getConfigFromEnv();
        return self::createManagerFromConnections($connections);
    }

    public static function createManagerFromConnections(array $connections): Manager
    {
        $databaseConfigFile = getenv('DATABASE_CONFIG_FILE');
        if($databaseConfigFile) {
            $config = StoreHelper::load(getenv('DATABASE_CONFIG_FILE'));
        } else {
            $config = [];
        }

//        $config = LoadHelper::loadConfig(getenv('DATABASE_CONFIG_FILE'));
//        $connectionMap = ExtExtArrayHelper::getValue($config, 'connection.connectionMap', []);

        $map = ArrayPathHelper::getValue($config, 'connection.map', []);
//        $tableAlias = self::createTableAlias($connections, $map);
        $capsule = new Manager();
//        $capsule->setConnectionMap($connectionMap);
//        $capsule->setTableAlias($tableAlias);

        self::touchSqlite($connections);

        foreach ($connections as $connectionName => $connection) {
            if($connection) {
                $capsule->addConnection($connection, $connectionName);
            }
        }
        return $capsule;
    }

    public static function touchSqlite(array $connections)
    {
        foreach ($connections as $connectionName => $connectionConfig) {
            if (isset($connectionConfig['driver']) && $connectionConfig['driver'] == DbDriverEnum::SQLITE && $connectionConfig['database'] != ':memory:') {
//                $isExists = file_exists($connectionConfig['database']);
                FileStorageHelper::touchFile($connectionConfig['database']);
            }
        }
    }

    public static function createTableAlias(array $connections, array $configMap): TableAlias
    {
        $tableAlias = new TableAlias;
        foreach ($connections as $connectionName => $connectionConfig) {
            if (!isset($connectionConfig['map'])) {
                $connectionConfig['map'] = $configMap;
            }
            $map = ArrayPathHelper::getValue($connectionConfig, 'map', []);
            if (isset($connectionConfig['driver']) && $connectionConfig['driver'] !== DbDriverEnum::PGSQL) {
                foreach ($map as $from => &$to) {
                    $to = str_replace('.', '_', $to);
                }
            }
            $tableAlias->addMap($connectionName, $map);
        }
        return $tableAlias;
    }
}