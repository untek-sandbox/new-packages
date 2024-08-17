<?php

namespace Untek\Model\EntityManager;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Untek\Model\EntityManager\Attribute\AsEntityRepository;
use Untek\Model\EntityManager\DependencyInjection\EntityManagerPass;

class EntityManagerBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EntityManagerPass());

        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($container, $fileLocator);
        $loader->load(__DIR__ . '/resources/config/services/entity-manager.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->registerAttributeForAutoconfiguration(AsEntityRepository::class, static function (ChildDefinition $definition, AsEntityRepository $attribute): void {
            $definition->addTag('repository');
        });
    }
}
