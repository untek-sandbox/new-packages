<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\FrameworkPlugin\RestApiLanguage\Infrastructure\Subscribers\RestApiLocaleSubscriber;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services->set(RestApiLocaleSubscriber::class)
        ->arg('$languageCodes', param('app_locales_languages'));
};