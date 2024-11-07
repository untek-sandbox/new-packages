<?php

namespace Untek\Database;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DatabaseBundle extends AbstractBundle
{

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new \Untek\Database\Base\Infrastructure\DependencyInjection\DatabaseExtension();
    }
}