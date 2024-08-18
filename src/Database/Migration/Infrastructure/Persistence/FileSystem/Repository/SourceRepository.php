<?php

namespace Untek\Database\Migration\Infrastructure\Persistence\FileSystem\Repository;

use Untek\Component\Arr\Helpers\ArrayHelper;
use Untek\Component\Code\Exceptions\DeprecatedException;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Core\FileSystem\Helpers\FindFileHelper;
use Untek\Database\Migration\Domain\Model\Migration;

class SourceRepository
{

    public function __construct(private string $configPath)
    {
    }

    public function getAll(): array
    {
        $directories = include $this->configPath;

        if (empty($directories)) {
            throw new InvalidConfigException('Empty directories configuration for migration!');
        }
        $classes = [];
        foreach ($directories as $dir) {
            if(is_dir($dir)) {
                $migrationDir = realpath($dir);
            } else {
                throw new \Exception("Migration directory \"$dir\" not found!");
            }
            $newClasses = self::scanDir($migrationDir);
            $classes = ArrayHelper::merge($classes, $newClasses);
        }
        return $classes;
    }

    private static function scanDir(string $dir): array
    {
        $files = FindFileHelper::scanDir($dir);
        $classes = [];
        foreach ($files as $file) {
            $classNameClean = FilePathHelper::fileRemoveExt($file);
            $entity = new Migration;
            $entity->className = 'Migrations\\' . $classNameClean;
            $entity->fileName = $dir . DIRECTORY_SEPARATOR . $classNameClean . '.php';
            $entity->version = $classNameClean;
            include_once($entity->fileName);
            $classes[] = $entity;
        }
        return $classes;
    }

}