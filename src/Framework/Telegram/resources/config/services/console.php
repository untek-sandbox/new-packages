<?php

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\PersistingStoreInterface;
use Symfony\Component\Lock\Store\SemaphoreStore;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Core\Instance\Libs\InstanceProvider;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Repositories\File\StoreRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\RequestRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\UpdatesRepository;
use Untek\Framework\Telegram\Domain\Repositories\Telegram\ResponseRepository as TelegramResponseRepository;
use Untek\Framework\Telegram\Domain\Repositories\Test\ResponseRepository as TestResponseRepository;
use Untek\Framework\Telegram\Domain\Services\BotService;
use Untek\Framework\Telegram\Domain\Services\LongPullService;
use Untek\Framework\Telegram\Domain\Services\RequestService;
use Untek\Framework\Telegram\Domain\Services\ResponseService;
use Untek\Framework\Telegram\Domain\Services\RouteService;
use Untek\Framework\Telegram\Symfony4\Commands\LongPullCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(LongPullCommand::class, LongPullCommand::class)
        ->args(
            [
                service(LongPullService::class),
                service(ConfigRepository::class),
                service(LockFactory::class),
                service(ContainerInterface::class),
            ]
        )
        ->tag('console.command');
};