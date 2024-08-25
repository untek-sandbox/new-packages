<?php

namespace Untek\Database\Base\Domain\Libs;

use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Yiisoft\Arrays\ArrayHelper;

class TableAlias
{

    private $map = null;
    private $connectionMaps = [];

    public function addMap(string $connectionName, array $map)
    {
        $this->connectionMaps[$connectionName] = $map;
    }

    public function encode(string $connectionName, string $sourceTableName)
    {
        $map = $this->connectionMaps[$connectionName];
        $targetTableName = ExtArrayHelper::getValue($map, $sourceTableName, $sourceTableName);
        return $targetTableName;
    }

    public function decode(string $connectionName, string $targetTableName)
    {
        $map = $this->connectionMaps[$connectionName];
        $map = array_flip($map);
        $sourceTableName = ExtArrayHelper::getValue($map, $targetTableName, $targetTableName);
        return $sourceTableName;
    }

}