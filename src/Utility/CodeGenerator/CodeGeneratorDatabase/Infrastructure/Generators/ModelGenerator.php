<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;

class ModelGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/model.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $className = DatabasePathHelper::getModelClass($command);
        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}