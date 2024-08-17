<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\RestApiPathHelper;

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
        $commandClassName = Inflector::camelize($commandClassName);
        $controllerClassName = RestApiPathHelper::getControllerClass($command);
        $schemaClassName = RestApiPathHelper::getRestApiSchemaClass($command);
        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'schemaClassName' => $schemaClassName,
            'routeName' => $routeName = $command->getHttpMethod() . '_' . $command->getUri(),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($controllerClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($controllerClassName);
        $this->collection->add(new FileResult($fileName, $code));
    }
}