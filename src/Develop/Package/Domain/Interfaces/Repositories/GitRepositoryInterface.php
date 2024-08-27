<?php

namespace Untek\Develop\Package\Domain\Interfaces\Repositories;

use Doctrine\Common\Collections\Collection;
use Untek\Develop\Package\Domain\Entities\PackageEntity;
use Untek\Model\Shared\Interfaces\GetEntityClassInterface;

interface GitRepositoryInterface //extends GetEntityClassInterface
{

    public function isHasChanges(PackageEntity $packageEntity): bool;

    public function allChanged();

    public function allVersion(PackageEntity $packageEntity);

    public function allCommit(PackageEntity $packageEntity): Collection;

    public function allTag(PackageEntity $packageEntity): Collection;
}
