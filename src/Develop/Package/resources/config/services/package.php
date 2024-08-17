<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Develop\Package\Commands\DepsCommand;
use Untek\Develop\Package\Commands\DepsUnusedCommand;
use Untek\Develop\Package\Commands\GitBranchByVersionCommand;
use Untek\Develop\Package\Commands\GitBranchCheckoutToRootCommand;
use Untek\Develop\Package\Commands\GitBranchCommand;
use Untek\Develop\Package\Commands\GitChangedCommand;
use Untek\Develop\Package\Commands\GithubOrgsCommand;
use Untek\Develop\Package\Commands\GitNeedReleaseCommand;
use Untek\Develop\Package\Commands\GitPullCommand;
use Untek\Develop\Package\Commands\GitPushCommand;
use Untek\Develop\Package\Commands\GitStashAllCommand;
use Untek\Develop\Package\Commands\GitVersionCommand;
use Untek\Develop\Package\Domain\Interfaces\Repositories\GitRepositoryInterface;
use Untek\Develop\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface;
use Untek\Develop\Package\Domain\Interfaces\Services\GitServiceInterface;
use Untek\Develop\Package\Domain\Interfaces\Services\PackageServiceInterface;
use Untek\Develop\Package\Domain\Repositories\File\GroupRepository;

use Untek\Develop\Package\Domain\Repositories\File\PackageRepository;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();
    $parameters = $configurator->parameters();

    $services->set(
        \Untek\Tool\Dev\Composer\Domain\Interfaces\Repositories\ConfigRepositoryInterface::class,
        \Untek\Tool\Dev\Composer\Domain\Repositories\File\ConfigRepository::class
    );
    $services->set(
        \Untek\Tool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface::class,
        \Untek\Tool\Dev\Composer\Domain\Services\ConfigService::class
    );
    $services->set(
        \Untek\Develop\Package\Domain\Interfaces\Services\GitServiceInterface::class,
        \Untek\Develop\Package\Domain\Services\GitService::class
    )
        ->args(
            [
                service(GitRepositoryInterface::class),
                service(PackageServiceInterface::class),
            ]
        );
    $services->set(
        \Untek\Develop\Package\Domain\Interfaces\Services\PackageServiceInterface::class,
        \Untek\Develop\Package\Domain\Services\PackageService::class
    )
        ->args(
            [
                service(PackageRepositoryInterface::class),
            ]
        );

    $fileName = getenv('PACKAGE_GROUP_CONFIG') ? getenv(
        'PACKAGE_GROUP_CONFIG'
    ) : __DIR__ . '/../../../Domain/Data/package_group.php';
    $services->set(
        \Untek\Develop\Package\Domain\Repositories\File\GroupRepository::class,
        \Untek\Develop\Package\Domain\Repositories\File\GroupRepository::class
    )
        ->args(
            [
                $fileName,
            ]
        );

    $services->set(
        \Untek\Develop\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface::class,
        \Untek\Develop\Package\Domain\Repositories\File\PackageRepository::class
    )
        ->args(
            [
                service(GroupRepository::class),
            ]
        );

    $services->alias(
        \Untek\Develop\Package\Domain\Interfaces\Repositories\GitRepositoryInterface::class,
        \Untek\Develop\Package\Domain\Repositories\File\GitRepository::class
    );
    $services->set(
        \Untek\Develop\Package\Domain\Repositories\File\GitRepository::class,
        \Untek\Develop\Package\Domain\Repositories\File\GitRepository::class
    )
        ->args(
            [
                service(PackageRepositoryInterface::class),
            ]
        );
    
    $commands = [
        DepsCommand::class,
        DepsUnusedCommand::class,
        GitBranchByVersionCommand::class,
        \Untek\Develop\Package\Commands\GitCheckoutCommand::class,
        GitBranchCheckoutToRootCommand::class,
        GitBranchCommand::class,
        GithubOrgsCommand::class,
        GitNeedReleaseCommand::class,
        GitPullCommand::class,
        GitPushCommand::class,
        GitStashAllCommand::class,
        GitVersionCommand::class,
    ];
    foreach ($commands as $commandClass) {
        $services->set($commandClass, $commandClass)
            ->args(
                [
                    service(PackageServiceInterface::class),
                    service(GitServiceInterface::class),
                ]
            )
            ->tag('console.command');
    }

    $services->set(GitChangedCommand::class)
        ->args(
            [
                service(PackageRepositoryInterface::class),
                service(GitServiceInterface::class),
            ]
        )
        ->tag('console.command');

};