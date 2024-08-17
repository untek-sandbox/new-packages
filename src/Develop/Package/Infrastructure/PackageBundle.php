<?php

namespace Untek\Develop\Package\Infrastructure;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Untek\Core\Kernel\Bundle\BaseBundle;

class PackageBundle extends BaseBundle
{

    public function getName(): string
    {
        return 'package';
    }

    public function build(ContainerBuilder $containerBuilder)
    {
        $this->importServices($containerBuilder, __DIR__ . '/../Resources/config/services/package.php');
    }
}
