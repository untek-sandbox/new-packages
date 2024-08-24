<?php

namespace Untek\Utility\CodeGenerator\Application\Infrastructure\Generators;

use Untek\Component\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Application\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Infrastructure\Helpers\ApplicationPathHelper;

class ContainerConfigBusGenerator implements GeneratorInterface
{

    private string $template = __DIR__ . '/../../resources/templates/command-bus-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateApplicationCommand $command): void
    {
        $handlerClassName = ApplicationPathHelper::getHandlerClass($command);
        $commandClassName = ApplicationPathHelper::getCommandClass($command);
        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/command-bus.php';
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $fileName, $template);
        if (!$configGenerator->hasCode($handlerClassName)) {
            $controllerDefinition =
                '    $configurator->define(\\' . $commandClassName . '::class, \\' . $handlerClassName . '::class);';
            $code = $configGenerator->appendCode($controllerDefinition);
            $this->collection->addFile(new FileResult($fileName, $code));
        }
    }
}