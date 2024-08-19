<?php

namespace Untek\Core\App\Libs;

use Psr\Container\ContainerInterface;
use Untek\Component\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class ContainerWrapper implements ContainerInterface
{

    public function __construct(private ContainerInterface $container)
    {
    }

    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}
