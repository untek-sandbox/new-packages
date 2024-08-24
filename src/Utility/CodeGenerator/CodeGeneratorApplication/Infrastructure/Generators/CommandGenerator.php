<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorApplication\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationPathHelper;

class CommandGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/command.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($commandClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($commandClassName);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}