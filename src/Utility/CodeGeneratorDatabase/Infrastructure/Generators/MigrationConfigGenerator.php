<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Component\Package\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;

class MigrationConfigGenerator
{

    private string $template = __DIR__ . '/../../resources/templates/migration-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection, private string $namespace, private string $migrationConfigFile)
    {
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $fileName = PackageHelper::pathByNamespace($this->namespace) . '/resources/migrations';
        $fileName = (new Filesystem())->makePathRelative($fileName, realpath(__DIR__ . '/../../../../../../../sf-blank'));
        $fileName = rtrim($fileName, '/');
        $concreteCode = $fileName;
        $codeForAppend = "    __DIR__ . '/../../$fileName',";
        $configFile = $this->migrationConfigFile;
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $template);
        if (!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->add(new FileResult($configFile, $code));
        }
    }
}