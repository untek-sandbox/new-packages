<?php

namespace Untek\User\Authentication\Application\Services;

use Untek\Persistence\Contract\Interfaces\RepositoryCrudInterface;
use Untek\User\Authentication\Domain\Model\UserAssignedRoles;

interface UserAssignedRolesRepositoryInterface extends RepositoryCrudInterface
{

    /**
     * @param int $id
     * @return UserAssignedRoles[]
     */
    public function findByUserId(int $id): array;
}