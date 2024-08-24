<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Helpers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Instance\Exceptions\NotInstanceOfException;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\CommandInterface;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;

class GeneratorHelper
{

    public static function generate(array $generators, object $command): void
    {
        foreach ($generators as $generator) {
            /** @var GeneratorInterface $generator */
            if (!$generator instanceof GeneratorInterface) {
                throw new NotInstanceOfException(sprintf('Class "%s" not instance of "%s".', get_class($generator), GeneratorInterface::class));
            }
            if (!$command instanceof CommandInterface) {
                throw new NotInstanceOfException(sprintf('Class "%s" not instance of "%s".', get_class($command), CommandInterface::class));
            }
            $generator->generate($command);
        }
    }

    public static function dump(GenerateResultCollection $collection): void
    {
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            if ($result instanceof FileResult) {
                if ($result->getName() != '') {
                    $dir = dirname($result->getName());
                    if (!is_dir($dir)) {
                        mkdir($dir, recursive: true);
                    }
                    $fs->dumpFile($result->getName(), $result->getContent());
                }
            }
        }
    }
}
