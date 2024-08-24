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
        $loader->load(__DIR__ . '/../CodeGeneratorApplication/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../CodeGeneratorCli/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../CodeGeneratorDatabase/resources/config/services/main.php');
        $loader->load(__DIR__ . '/../CodeGeneratorRestApi/resources/config/services/main.php');
    }
}
