<?php

use Psr\Container\ContainerInterface;
use Untek\Core\ConfigManager\Interfaces\ConfigManagerInterface;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Domain\Repositories\Telegram\ResponseRepository as TelegramResponseRepository;
use Untek\Framework\Telegram\Domain\Repositories\Test\ResponseRepository as TestResponseRepository;
use Untek\Framework\Telegram\Domain\Services\RouteService;
use Yiisoft\Arrays\ArrayHelper;

return [
    'singletons' => [
        RouteService::class => function (ContainerInterface $container) {
            /** @var ConfigManagerInterface $configManager */
            $configManager = $container->get(ConfigManagerInterface::class);
            $telegramRoutes = $configManager->get('telegramRoutes', []);
            $routeService = new RouteService();
            $routes = [];
            foreach ($telegramRoutes as $containerConfig) {
                $requiredConfig = require($containerConfig);
                $routes = ArrayHelper::merge($routes, $requiredConfig);
            }
            $routeService->setDefinitions($routes);
            return $routeService;
        },
        ResponseRepositoryInterface::class =>
            (getenv('APP_ENV') == 'test') ?
                TestResponseRepository::class :
                TelegramResponseRepository::class,
        ConfigRepository::class => function (ContainerInterface $container) {
            /** @var \Untek\Core\App\Interfaces\EnvStorageInterface $envStorage */
            $envStorage = $container->get(\Untek\Core\App\Interfaces\EnvStorageInterface::class);

            $repo = new ConfigRepository($envStorage->get('TELEGRAM_BOT_TOKEN') ?: null);
            return $repo;
        },
    ],
];
