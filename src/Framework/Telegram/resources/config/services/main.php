<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Repositories\File\StoreRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\RequestRepository;
use Untek\Framework\Telegram\Domain\Repositories\Http\UpdatesRepository;
use Untek\Framework\Telegram\Domain\Repositories\Telegram\ResponseRepository as TelegramResponseRepository;
use Untek\Framework\Telegram\Domain\Repositories\Test\ResponseRepository as TestResponseRepository;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Framework\Telegram\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
        ]);


    $services->set(StoreRepository::class)
    ->args([
        getenv('VAR_DIRECTORY') . '/telegram/server/state.json',
    ]);

    $services->set(UpdatesRepository::class);
    $services->set(ConfigRepository::class)
        ->args([
            getenv('TELEGRAM_BOT_TOKEN'),
        ]);

    $services->set(RequestRepository::class);

    if (getenv('APP_ENV') == 'test') {
        $services->set(TestResponseRepository::class)
            ->args([
                service(RequestService::class),
                getenv('VAR_DIRECTORY') . '/telegram/response',
            ]);
        $services->alias(ResponseRepositoryInterface::class, TestResponseRepository::class);
    } else {
        $services->set(TelegramResponseRepository::class);
        $services->alias(ResponseRepositoryInterface::class, TelegramResponseRepository::class);
    }
};