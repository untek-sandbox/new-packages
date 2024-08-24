<?php

namespace Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Core\App\Services\ControllerAccessChecker;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Helpers\RestApiPathHelper;

class ContainerConfigGenerator implements GeneratorInterface
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
        $consoleConfigGenerator = new \Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\ContainerConfigGenerator(
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