<?php

namespace Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Helpers\RestApiPathHelper;

class RestApiSchemeGenerator implements GeneratorInterface
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/rest-api-schema.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        if($command->getParameter(self::class, 'skip') == true) {
            return;
        }
        $commandFullClassName = ApplicationPathHelper::getCommandClass($command);
        $commandClassName = ClassHelper::getClassOfClassName($commandFullClassName);
        $commandClassName = (new Inflector())->toPascalCase($commandClassName);
        $schemaClassName = RestApiPathHelper::getRestApiSchemaClass($command);
        $modelClassName = \Untek\Utility\CodeGenerator\Database\Infrastructure\Helpers\DatabasePathHelper::getModelClass($command);
        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'modelClassName' => $command->getModelName() ? $modelClassName : null,
            'properties' => $command->getProperties(),
        ];
        /*if(empty($command->getModelName())) {
            $template = $this->template;
        } else {
        }*/
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
//        dd($template);
        $code = $this->codeGenerator->generatePhpClassCode($schemaClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($schemaClassName);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}