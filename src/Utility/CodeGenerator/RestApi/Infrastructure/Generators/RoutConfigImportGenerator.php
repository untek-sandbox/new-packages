<?php

namespace Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generators;

use Untek\Component\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGenerator\RestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\RestApi\Infrastructure\Generator\RoutesLoadConfigGenerator;

class RoutConfigImportGenerator implements GeneratorInterface
{

    private string $template = __DIR__ . '/../../resources/templates/routes-load-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';
        $this->generateConfig($command, $modulePath, '/v' . $command->getVersion());
    }

    protected function generateConfig(GenerateRestApiCommand $command, string $modulePath, string $prefix = null): void
    {
        $modulePath = ltrim($modulePath, '/');
        $codeForAppend = '    $routes
        ->import(__DIR__ . \'/../../' . $modulePath . '\')
        ->prefix(\'' . $prefix . '\');';
        $configFile = __DIR__ . '/../../../../../../../../config/rest-api/routes.php';
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $template);
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->addFile(new FileResult($configFile, $code));
        }
    }
}