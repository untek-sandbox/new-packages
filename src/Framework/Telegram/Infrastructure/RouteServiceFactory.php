<?php

namespace Untek\Framework\Telegram\Infrastructure;

use Untek\Core\Instance\Libs\InstanceProvider;
use Untek\Framework\Telegram\Domain\Services\RouteService;

class RouteServiceFactory
{

    public static function createService(
        InstanceProvider $instanceProvider,
        array $telegramRoutes,
    ): RouteService
    {
        $routes = [];
        foreach ($telegramRoutes as $containerConfig) {
            $requiredConfig = require($containerConfig);
            $routes = array_merge($routes, $requiredConfig);
        }
        $routeService = new RouteService();
        $routeService->setDefinitions($routes);
        return $routeService;
    }
}
