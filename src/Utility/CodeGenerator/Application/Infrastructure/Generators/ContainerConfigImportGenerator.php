<?php

namespace Untek\Utility\CodeGenerator\Application\Infrastructure\Generators;

use Untek\Component\Package\Helpers\ComposerHelper;
use Untek\Component\FileSystem\Helpers\FilePathHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\ContainerLoadConfigGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigImportGenerator implements GeneratorInterface
{

    public function __construct(protected GenerateResultCollection $collection, private ?string $moduleConfigFilePath = null, private ?string $appConfigFilePath = null)
    {
    }

    public function generate(object $command): void
    {
//        $handlerClassName = ApplicationPathHelper::getHandlerClass($command);
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        if (realpath($path) === false) {
            $up = FilePathHelper::up($path);
            $basename = basename($path);
            $path = $up . '/' . $basename;
        }
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $this->moduleConfigFilePath ? $relative . $this->moduleConfigFilePath : $relative . '/resources/config/services/main.php';
        $consoleLoadConfigGenerator = new ContainerLoadConfigGenerator($this->collection, $command->getNamespace());
        $consoleLoadConfigGenerator->generate($modulePath, $this->appConfigFilePath);
    }
}