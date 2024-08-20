<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\RestApiPathHelper;

class ContainerConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $controllerClassName = RestApiPathHelper::getControllerClass($command);
        $args = [
            '\\'.CommandBusInterface::class,
            UrlGeneratorInterface::class,
            ControllerAccessChecker::class,
        ];
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator(
            $this->collection,
            $command->getNamespace(),
            null,
            '/resources/config/services/rest-api.php'
        );
        $uri = 'v' . $command->getVersion() . '/' . $command->getUri();
        $routeName = $command->getHttpMethod() . '_' . $uri;
        $tags = [
            [
                'name' => 'http.controller',
                'data' => [
                    'name' => $routeName,
                    'path' => '/rest-api/' . $uri,
                    'methods' => [$command->getHttpMethod()],
                ],
            ],
        ];
        $consoleConfigGenerator->generate($controllerClassName, $controllerClassName, $args, $tags);
    }
}