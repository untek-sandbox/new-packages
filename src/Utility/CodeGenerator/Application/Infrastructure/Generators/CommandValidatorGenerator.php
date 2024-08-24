<?php

namespace Untek\Utility\CodeGenerator\Application\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;

class CommandValidatorGenerator implements GeneratorInterface
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/validator.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $validatorClassName = ApplicationPathHelper::getCommandValidatorClass($command);
        $params = [
            'properties' => ApplicationHelper::prepareProperties($command),
            'commandClassName' => $commandClassName,
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($validatorClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($validatorClassName);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}