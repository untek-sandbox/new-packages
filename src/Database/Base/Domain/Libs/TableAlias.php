<?php

namespace Untek\Database\Base\Domain\Libs;

use Untek\Component\Arr\Helpers\ArrayPathHelper;

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
        $targetTableName = ArrayPathHelper::getValue($map, $sourceTableName, $sourceTableName);
        return $targetTableName;
    }

    public function decode(string $connectionName, string $targetTableName)
    {
        $map = $this->connectionMaps[$connectionName];
        $map = array_flip($map);
        $sourceTableName = ArrayPathHelper::getValue($map, $targetTableName, $targetTableName);
        return $sourceTableName;
    }

}