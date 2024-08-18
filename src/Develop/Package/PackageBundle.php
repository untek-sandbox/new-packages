<?php

namespace Untek\Develop\Package;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class PackageBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/resources/config/services/package.php');
    }
}
