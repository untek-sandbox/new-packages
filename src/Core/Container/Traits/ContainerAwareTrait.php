<?php

namespace Untek\Core\Container\Traits;

use Psr\Container\ContainerInterface;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

trait ContainerAwareTrait
{

    public function __construct(protected ?ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
