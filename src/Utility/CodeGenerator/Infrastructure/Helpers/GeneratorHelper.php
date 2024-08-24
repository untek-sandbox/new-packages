<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Helpers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Component\FileSystem\Helpers\FileStorageHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Interfaces\GeneratorInterface;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

class GeneratorHelper
{

    public static function generate(array $generators, object $command): void
    {
        foreach ($generators as $generator) {
            /** @var GeneratorInterface $generator */
            $generator->generate($command);
        }
    }

    public static function dump(GenerateResultCollection $collection): void
    {
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            if($result instanceof FileResult) {
                if($result->getName() != '') {
                    $dir = dirname($result->getName());
                    if(!is_dir($dir)) {
                        mkdir($dir, recursive: true);
                    }
                    $fs->dumpFile($result->getName(), $result->getContent());
                }
            }
        }
    }
}
