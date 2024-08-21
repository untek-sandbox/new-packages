<?php

namespace Untek\Database\Base\Domain\Traits;

use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Core\DotEnv\Domain\Libs\DotEnv;
use Illuminate\Database\Capsule\Manager;
use Untek\Database\Base\Domain\Libs\TableAlias;
use function Kreait\Firebase\Auth\CreateSessionCookie\response;

DeprecateHelper::hardThrow();

trait TableNameTrait
{

    protected $tableName;

    public function getTableName(): string
    {
        return $this->tableName;
    }

//    abstract public function getCapsule(): Manager;

    /*public function connectionName()
    {
        return $this
            ->getCapsule()
            ->getConnectionNameByTableName($this->getTableName());
    }*/

    /*public function tableNameAlias(): string
    {
        return $this->getTableName();
//        return $this->encodeTableName($this->getTableName());
    }*/

    /*protected function getAlias(): TableAlias
    {
        return $this
            ->getCapsule()
            ->getAlias();
    }*/
    
//    public function encodeTableName(string $sourceTableName, string $connectionName = null): string
//    {
//        return $sourceTableName;
//
//        /*$connectionName = $connectionName ?: $this->connectionName();
//        $targetTableName = $this
//            ->getCapsule()
//            ->getAlias()
//            ->encode($connectionName, $sourceTableName);
//        return $targetTableName;*/
//    }
}