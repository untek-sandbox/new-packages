<?php

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Framework\WebSocket\Application\Services\MessageTransportInterface;
use Untek\Framework\WebSocket\Infrastructure\Services\SocketDaemon;
use Untek\Framework\WebSocket\Infrastructure\Services\SocketDaemonTest;
use Untek\Framework\WebSocket\Infrastructure\Storage\ConnectionRamStorage;
use Untek\Framework\WebSocket\Presentation\Cli\Commands\SendMessageToSocketCommand;
use Untek\Framework\WebSocket\Presentation\Cli\Commands\SocketCommand;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\Framework\WebSocket\Application\Services\SocketDaemonInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Untek\Framework\WebSocket\Application\Handlers\SendMessageToWebSocketCommandHandler;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Framework\WebSocket\\', __DIR__ . '/../../../')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
        ]);

    $services->set(ConnectionRamStorage::class);

    if (getenv('APP_MODE') === 'test') {
        $services->set(SocketDaemon::class, SocketDaemonTest::class);
    } else {
        $services->set(SocketDaemon::class)
            ->args([
                service(EventDispatcherInterface::class),
                service(ConnectionRamStorage::class),
                service(TokenServiceInterface::class),
                getenv('WEB_SOCKET_LOCAL_URL'),
                getenv('WEB_SOCKET_CLIENT_URL'),
                getenv('APP_ENV'),
            ]);
    }

    $services->alias(SocketDaemonInterface::class, SocketDaemon::class);
    $services->alias(MessageTransportInterface::class, SocketDaemon::class);

    /*$services->set(SocketCommand::class, SocketCommand::class)
        ->args([
            service(SocketDaemonInterface::class),
        ])
        ->tag('console.command');

    $services->set(SendMessageToSocketCommand::class, SendMessageToSocketCommand::class)
        ->args([
            service(CommandBusInterface::class),
        ])
        ->tag('console.command');*/
};