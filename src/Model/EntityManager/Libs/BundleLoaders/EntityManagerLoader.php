<?php

namespace Untek\Model\EntityManager\Libs\BundleLoaders;

use Untek\Core\Bundle\Base\BaseLoader;
use Untek\Core\Instance\Libs\Resolvers\MethodParametersResolver;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;

/**
 * Загрузчик конфигурации менеджера сущностей
 */
class EntityManagerLoader extends BaseLoader
{

    public function loadAll(array $bundles): void
    {
        /*$config = $this->getValueFromCache();
        if (empty($config)) {
            $config = $this->getConfig($bundles);
            $this->setValueToCache($config);
        }*/
        $config = $this->getConfig($bundles);
        /** @var EntityManagerConfiguratorInterface $entityManagerConfigurator */
        $entityManagerConfigurator = $this->getContainer()->get(EntityManagerConfiguratorInterface::class);
        $entityManagerConfigurator->setConfig($config);
    }

    protected function getConfig(array $bundles): array
    {
        foreach ($bundles as $bundle) {
            $containerConfigList = $this->load($bundle);
            foreach ($containerConfigList as $containerConfig) {
                $this->importFromConfig($containerConfig);
            }
        }
        /** @var EntityManagerConfiguratorInterface $entityManagerConfigurator */
        $entityManagerConfigurator = $this->getContainer()->get(EntityManagerConfiguratorInterface::class);
        return $entityManagerConfigurator->getConfig();
    }

    private function importFromConfig($configFile): void
    {
        $requiredConfig = require($configFile);
        if (is_array($requiredConfig)) {
            $this->loadFromArray($requiredConfig);
        } elseif (is_callable($requiredConfig)) {
            $this->loadFromCallback($requiredConfig);
        }
    }

    private function loadFromArray(array $requiredConfig): void
    {
        /** @var EntityManagerConfiguratorInterface $entityManagerConfigurator */
        $entityManagerConfigurator = $this
            ->getContainer()
            ->get(EntityManagerConfiguratorInterface::class);
        if (!empty($requiredConfig['entities'])) {
            foreach ($requiredConfig['entities'] as $entityClass => $repositoryInterface) {
                $entityManagerConfigurator->bindEntity($entityClass, $repositoryInterface);
            }
        }
    }

    private function loadFromCallback(callable $requiredConfig): void
    {
        /** @var EntityManagerConfiguratorInterface $entityManagerConfigurator */
        $entityManagerConfigurator = $this->getContainer()->get(EntityManagerConfiguratorInterface::class);
        $methodParametersResolverArgs[] = $entityManagerConfigurator;
        $methodParametersResolver = new MethodParametersResolver($this->getContainer());
        $params = $methodParametersResolver->resolveClosure($requiredConfig, $methodParametersResolverArgs);
        call_user_func_array($requiredConfig, $params);
    }
}
