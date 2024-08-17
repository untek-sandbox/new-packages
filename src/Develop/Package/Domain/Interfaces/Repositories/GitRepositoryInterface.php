<?php

namespace Untek\Develop\Package\Domain\Interfaces\Repositories;

use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Shared\Interfaces\GetEntityClassInterface;
use Untek\Develop\Package\Domain\Entities\PackageEntity;

interface GitRepositoryInterface //extends GetEntityClassInterface
{

    public function isHasChanges(PackageEntity $packageEntity): bool;

    public function allChanged();

    public function allVersion(PackageEntity $packageEntity);

    public function allCommit(PackageEntity $packageEntity): Enumerable;

    public function allTag(PackageEntity $packageEntity): Enumerable;
}
