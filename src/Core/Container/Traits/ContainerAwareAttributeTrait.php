<?php

namespace Untek\Core\Container\Traits;

use JetBrains\PhpStorm\Deprecated;
use Psr\Container\ContainerInterface;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

trait ContainerAwareAttributeTrait
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    protected function ensureContainer(ContainerInterface $container = null): ?ContainerInterface
    {
        return $container ?: $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
