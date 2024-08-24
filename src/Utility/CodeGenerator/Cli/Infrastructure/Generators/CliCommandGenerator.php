<?php

namespace Untek\Utility\CodeGenerator\Cli\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGenerator\Cli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGenerator\Cli\Infrastructure\Helpers\CliPathHelper;

class CliCommandGenerator implements GeneratorInterface
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/cli-command.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateCliCommand $command): void
    {
        $commandFullClassName = $command->getCommandClass();
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = (new Inflector())->toPascalCase($commandClassName);
        $cliCommandClassName = CliPathHelper::getCliCommandClass($command);
        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'cliCommandName' => $command->getCliCommand(),
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $fileName = GeneratorFileHelper::getFileNameByClass($cliCommandClassName);
        $code = $this->codeGenerator->generatePhpClassCode($cliCommandClassName, $template, $params);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}