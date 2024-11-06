<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\ChainUserProvider;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\User\Authentication\Application\Services\UserAssignedRolesRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\CredentialServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Infrastructure\Persistence\Eloquent\Repository\TokenRepository;
use Untek\User\Authentication\Infrastructure\Persistence\Eloquent\Repository\UserAssignedRolesRepository;
use Untek\User\Authentication\Infrastructure\Persistence\Eloquent\Repository\UserCredentialRepository;
use Untek\User\Authentication\Infrastructure\UserProviders\MockApiTokenUserProvider;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure()
        ->bind('$credentialTypes', ['login', 'phone']);

    $services
        ->load('Untek\User\Authentication\\', __DIR__ . '/../../../')
        ->exclude([
            __DIR__ . '/../../../Application/**/*{Command.php,Query.php}',
            __DIR__ . '/../../../{resources,Domain/Model}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
        ]);

    $services->set(TokenStorage::class);
    $services->alias(TokenStorageInterface::class, TokenStorage::class);
    $services->alias('security.token_storage', TokenStorage::class);

    $services->set(NativePasswordHasher::class);
    $services->alias(PasswordHasherInterface::class, NativePasswordHasher::class);

    $services->set(ChainUserProvider::class)
        ->args([
            [
                service(MockApiTokenUserProvider::class),
            ]
        ]);
    $services->alias(UserProviderInterface::class, ChainUserProvider::class);

    $services->alias(CredentialServiceInterface::class, UserCredentialRepository::class);
    $services->alias(TokenServiceInterface::class, TokenRepository::class);
    $services->alias(UserAssignedRolesRepositoryInterface::class, UserAssignedRolesRepository::class);
};