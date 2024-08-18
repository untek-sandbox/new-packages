<?php

namespace Untek\Model\Repository\Traits;

use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Model\Shared\Interfaces\GetEntityClassInterface;

DeprecateHelper::hardThrow();

trait RepositoryAwareTrait
{

    private $repository;

    /**
     * @return GetEntityClassInterface
     */
    protected function getRepository(): object
    {
        if ($this->repository) {
            return $this->repository;
        }
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityClass());
    }

    protected function setRepository(object $repository)
    {
        $this->repository = $repository;
    }
}
