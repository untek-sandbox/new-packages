<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Doctrine\Common\Collections\Collection;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;

interface ToastrRepositoryInterface
{

    /**
     * @param ToastrEntity $toastrEntity
     * @return mixed
     * @throws UnprocessibleEntityException
     */
    public function create(ToastrEntity $toastrEntity);

    /**
     * @return Collection | ToastrEntity[]
     */
    public function findAll(): Collection;
}
