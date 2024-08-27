<?php

namespace Untek\Utility\CodeGenerator\Application\Infrastructure\Generators;

use Untek\Component\Package\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\Application\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusImportGenerator implements GeneratorInterface
{

    private string $template = __DIR__ . '/../../resources/templates/command-bus-load-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClass($command);
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/command-bus.php';
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../..' . $modulePath . '\');';
        $fileName = __DIR__ . '/../../../../../../../../config/shared/command-bus.php';
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $fileName, $template);
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->addFile(new FileResult($fileName, $code));
        }
    }
}