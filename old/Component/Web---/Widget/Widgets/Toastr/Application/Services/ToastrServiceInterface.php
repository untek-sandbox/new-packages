<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

use Doctrine\Common\Collections\Collection;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;

interface ToastrServiceInterface
{

    public function success($message, int $delay = null);

    public function info($message, int $delay = null);

    public function warning($message, int $delay = null);

    public function error($message, int $delay = null);

    public function add(string $type, $message, int $delay = null);

    /**
     * @return Collection | ToastrEntity[]
     */
    public function findAll(): Collection;
}
