<?php

namespace Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\CodeGeneratorDatabase\Infrastructure\Helpers\DatabasePathHelper;

class EloquentRepositoryGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/eloquent-repository.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $repositoryDriver = $command->getRepositoryDriver();
        $modelClassName = DatabasePathHelper::getModelClass($command);
        $className = DatabasePathHelper::getRepositoryClass($command, $repositoryDriver);
        $normalizerClassName = DatabasePathHelper::getNormalizerClass($command);
        $interfaceClassName = DatabasePathHelper::getRepositoryInterface($command);
        $relationClassName = DatabasePathHelper::getRelationClass($command);
        $params = [
            'tableName' => $command->getTableName(),
            'interfaceClassName' => $interfaceClassName,
            'modelClassName' => $modelClassName,
            'normalizerClassName' => $normalizerClassName,
            'relationClassName' => $relationClassName,
        ];
//        $template = __DIR__ . '/../../resources/templates/' . $repositoryDriver . '-repository.php';
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}