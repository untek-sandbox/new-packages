<?php

namespace Untek\Develop\Package\Domain\Services;

//use Untek\Model\Service\Base\BaseCrudService;
use Untek\Develop\Package\Domain\Repositories\File\GroupRepository;

class GroupService extends BaseCrudService
{

    public function __construct(GroupRepository $repository)
    {
        $this->setRepository($repository);
    }

}
