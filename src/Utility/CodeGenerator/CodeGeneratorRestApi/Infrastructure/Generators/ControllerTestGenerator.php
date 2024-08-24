<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\CodeGeneratorRestApi\Infrastructure\Helpers\RestApiPathHelper;

class ControllerTestGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/rest-api-controller-test.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $controllerTestClassName = RestApiPathHelper::getControllerTestClass($command);
        $params = [
            'endpoint' => '/v' . $command->getVersion() . '/' . $command->getUri(),
            'method' => $command->getHttpMethod(),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($controllerTestClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($controllerTestClassName);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}