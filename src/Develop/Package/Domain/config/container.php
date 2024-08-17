<?php

return function (\Untek\Core\Container\Interfaces\ContainerConfiguratorInterface $configurator) {
    $configurator->singleton(\Untek\Tool\Dev\Composer\Domain\Interfaces\Repositories\ConfigRepositoryInterface::class, \Untek\Tool\Dev\Composer\Domain\Repositories\File\ConfigRepository::class);
    $configurator->singleton(\Untek\Tool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface::class, \Untek\Tool\Dev\Composer\Domain\Services\ConfigService::class);
    $configurator->singleton(\Untek\Develop\Package\Domain\Interfaces\Services\GitServiceInterface::class, \Untek\Develop\Package\Domain\Services\GitService::class);
    $configurator->singleton(\Untek\Develop\Package\Domain\Interfaces\Services\PackageServiceInterface::class, \Untek\Develop\Package\Domain\Services\PackageService::class);
    $configurator->singleton(\Untek\Develop\Package\Domain\Repositories\File\GroupRepository::class, function (\Psr\Container\ContainerInterface $container) {
        /** @var \Untek\Core\App\Interfaces\EnvStorageInterface $envStorage */
        $envStorage = $container->get(\Untek\Core\App\Interfaces\EnvStorageInterface::class);

        $fileName = $envStorage->get('PACKAGE_GROUP_CONFIG') ? $envStorage->get('PACKAGE_GROUP_CONFIG') : __DIR__ . '/../../../../../src/Domain/Data/package_group.php';
        $repo = new \Untek\Develop\Package\Domain\Repositories\File\GroupRepository($fileName);
        return $repo;
    });
    $configurator->singleton(\Untek\Develop\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface::class, \Untek\Develop\Package\Domain\Repositories\File\PackageRepository::class);
    $configurator->singleton(\Untek\Develop\Package\Domain\Interfaces\Repositories\GitRepositoryInterface::class, \Untek\Develop\Package\Domain\Repositories\File\GitRepository::class);

};
