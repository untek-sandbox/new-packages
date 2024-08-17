<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;

DeprecateHelper::hardThrow();

class DoctrineRepositoryGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/doctrine-repository.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $repositoryDriver = 'doctrine';
        $modelClassName = ApplicationPathHelper::getModelClass($command);
        $className = ApplicationPathHelper::getRepositoryClass($command, $repositoryDriver);
        $interfaceClassName = ApplicationPathHelper::getRepositoryInterface($command);
        $params = [
            'tableName' => $command->getTableName(),
            'interfaceClassName' => $interfaceClassName,
            'modelClassName' => $modelClassName,
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        $this->collection->add(new FileResult($fileName, $code));
    }
}