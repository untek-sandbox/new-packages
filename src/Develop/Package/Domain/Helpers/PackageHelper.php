<?php

namespace Untek\Develop\Package\Domain\Helpers;

use Untek\Develop\Package\Domain\Entities\ConfigEntity;
use Untek\Develop\Package\Domain\Entities\GroupEntity;
use Untek\Develop\Package\Domain\Entities\PackageEntity;

class PackageHelper
{

    /**
     * @return PackageEntity[]
     */
    public static function findAll(): array
    {
        $packages = self::getInstalled()['packages'];
        $collection = [];
        foreach ($packages as $package) {
            $packageEntity = new PackageEntity();
            $packageEntity->setId($package['name']);
            list($groupName, $packageName) = explode('/', $package['name']);
            $packageEntity->setName($packageName);

            $groupEntity = new GroupEntity();
            $groupEntity->name = $groupName;

            $packageEntity->setGroup($groupEntity);

            $confiEntity = new ConfigEntity();
            $confiEntity->setId($packageEntity->getId());
            $confiEntity->setConfig($package);
            $confiEntity->setPackage($packageEntity);

            $packageEntity->setConfig($confiEntity);
            $collection[] = $packageEntity;
        }
        return $collection;
    }

    public static function getInstalled(): array
    {
        $json = file_get_contents(__DIR__ . '/../../../../../../../../composer.lock');
        return json_decode($json, true);
    }
}
