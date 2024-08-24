<?php

namespace Untek\Utility\CodeGenerator\Database\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Component\Package\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Database\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\Database\Infrastructure\Helpers\DatabasePathHelper;

class FixtureGenerator implements GeneratorInterface
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/fixture.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $seedClassName = DatabasePathHelper::getSeedClass($command);
        $mainFileName = realpath(__DIR__ . '/../../../../../../../../../resources/seeds') . '/' . $command->getTableName() . '.php';
        $testFileName = realpath(__DIR__ . '/../../../../../../../../../tests/fixtures/seeds') . '/'  . $command->getTableName() . '.php';
        $params = [
            'seedClassName' => $seedClassName,
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generatePhpCode($template, $params);
        $this->collection->addFile(new FileResult($mainFileName, $code));
        $this->collection->addFile(new FileResult($testFileName, $code));
    }
}