<?php

namespace Untek\Utility\CodeGenerator\Database\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Component\Package\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Database\Application\Commands\GenerateDatabaseCommand;

class MigrationConfigGenerator implements GeneratorInterface
{

    private string $template = __DIR__ . '/../../resources/templates/migration-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection, private string $namespace, private string $migrationConfigFile)
    {
    }

    public function generate(GenerateDatabaseCommand $command): void
    {
        $fileName = PackageHelper::pathByNamespace($this->namespace) . '/resources/migrations';
        $fileName = (new Filesystem())->makePathRelative($fileName, realpath(__DIR__ . '/../../../../../../../../..'));
        $fileName = rtrim($fileName, '/');
        $concreteCode = $fileName;
        $codeForAppend = "    __DIR__ . '/../../$fileName',";
        $configFile = $this->migrationConfigFile;
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $template);
        if (!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->addFile(new FileResult($configFile, $code));
        }
    }
}