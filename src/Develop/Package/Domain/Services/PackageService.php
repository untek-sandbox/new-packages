<?php

namespace Untek\Develop\Package\Domain\Services;

//use Untek\Model\Service\Base\BaseCrudService;
use Untek\Develop\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface;
use Untek\Develop\Package\Domain\Interfaces\Services\PackageServiceInterface;

class PackageService extends BaseCrudService implements PackageServiceInterface
{

    public function __construct(private PackageRepositoryInterface $repository)
    {
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }
}
