<?php

namespace Untek\Utility\CodeGenerator\Database\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Database\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Helpers\DatabasePathHelper;

class RelationGenerator implements GeneratorInterface
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/relation.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $className = DatabasePathHelper::getRelationClass($command);
        $params = [
            'tableName' => $command->getTableName(),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpClassCode($className, $template, $params);
        $fileName = GeneratorFileHelper::getFileNameByClass($className);
        $this->collection->addFile(new FileResult($fileName, $code));
    }
}