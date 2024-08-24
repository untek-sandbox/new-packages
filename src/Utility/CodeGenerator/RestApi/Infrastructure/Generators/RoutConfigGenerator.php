<?php

namespace Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators;

use Untek\Component\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generator\RoutesConfigGenerator;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Helpers\RestApiPathHelper;

class RoutConfigGenerator implements GeneratorInterface
{

    private string $template = __DIR__ . '/../../resources/templates/route-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $controllerClassName = RestApiPathHelper::getControllerClass($command);
        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';
        $code = $this->generateConfig($fileName, $controllerClassName, $command);
        if ($code) {
            $this->collection->addFile(new FileResult($fileName, $code));
        }
    }

    protected function generateConfig(string $configFile, string $controllerClassName, GenerateRestApiCommand $command): ?string
    {
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $template);
        $code = null;
        if (!$configGenerator->hasCode($controllerClassName)) {
            $routeName = RestApiPathHelper::getControllerRouteName($command);
            $controllerDefinition =
                '    $routes
        ->add(\'' . $routeName . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $code = $configGenerator->appendCode($controllerDefinition);
        }
        return $code;
    }
}