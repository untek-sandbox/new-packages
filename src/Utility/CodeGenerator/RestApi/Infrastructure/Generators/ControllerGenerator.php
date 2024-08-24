<?php

namespace Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Helpers\RestApiPathHelper;

class ControllerGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/rest-api-controller.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = (new Inflector())->toPascalCase($commandClassName);
        $controllerClassName = RestApiPathHelper::getControllerClass($command);
        $schemaClassName = RestApiPathHelper::getRestApiSchemaClass($command);
        $routeName = RestApiPathHelper::getControllerRouteName($command);
        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'schemaClassName' => $schemaClassName,
            'routeName' => $routeName,
            'uri' => $command->getUri(),
            'method' => $command->getHttpMethod(),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($controllerClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($controllerClassName);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}