<?php

namespace Untek\Model\Contract\Interfaces;

use Doctrine\Persistence\ObjectRepository;

interface RepositoryCrudInterface extends
    RepositoryCountByInterface,
    RepositoryCreateInterface,
    RepositoryDeleteByIdInterface,
    RepositoryFindOneByIdInterface,
    RepositoryUpdateInterface,
    ObjectRepository
{
}
