<?php

namespace Untek\Utility\CodeGenerator\Application\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Helpers\DatabasePathHelper;

class CommandHandlerGenerator implements GeneratorInterface
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/handler.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClass($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $validatorClassName = ApplicationPathHelper::getCommandValidatorClass($command);
        $repositoryInterfaceClassName = DatabasePathHelper::getRepositoryInterface($command);

        $params = [
            'commandClassName' => $commandClassName,
            'validatorClassName' => $validatorClassName,
            'repositoryInterfaceClassName' => $repositoryInterfaceClassName,
            'modelName' => $command->getModelName(),
            'modelClass' => \Untek\Utility\CodeGenerator\Database\Infrastructure\Helpers\DatabasePathHelper::getModelClass($command),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($handlerClassName, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($handlerClassName);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}