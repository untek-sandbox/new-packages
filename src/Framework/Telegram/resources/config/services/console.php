<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Lock\LockFactory;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Services\LongPullService;
use Untek\Framework\Telegram\Symfony4\Commands\LongPullCommand;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

\Untek\Component\Dev\Helpers\DeprecateHelper::hardThrow();

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

};