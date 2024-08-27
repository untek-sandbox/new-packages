<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services;

use Doctrine\Common\Collections\Collection;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrRepositoryInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Enums\FlashMessageTypeEnum;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;
use Untek\Model\Service\Base\BaseService;

class ToastrService extends BaseService implements ToastrServiceInterface
{

    const DEFAULT_DELAY = 5000;

    public function __construct(ToastrRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }

    public function success($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::SUCCESS, $message, $delay);
    }

    public function info($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::INFO, $message, $delay);
    }

    public function warning($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::WARNING, $message, $delay);
    }

    public function error($message, int $delay = null)
    {
        $this->add(FlashMessageTypeEnum::ERROR, $message, $delay);
    }

    public function add(string $type, $message, int $delay = null)
    {
        if ($delay == null) {
            $delay = self::DEFAULT_DELAY;
        }
        if (is_array($message)) {
            $message = I18Next::translateFromArray($message);
        }
        $toastrEntity = new ToastrEntity();
        $toastrEntity->setType($type);
        $toastrEntity->setContent($message);
        $toastrEntity->setDelay($delay);
        $this->getRepository()->create($toastrEntity);
    }

    public function clear()
    {
        $this->getRepository()->clear();
    }

    public function findAll(): Collection
    {
        return $this->getRepository()->findAll();
    }
}
