<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CodeGeneratorBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../Application/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../Cli/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../Database/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../RestApi/resources/config/services/main.php');
    }
}
