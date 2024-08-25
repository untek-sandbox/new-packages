<?php

namespace Untek\Component\LogReader\Infrastructure\Persistence\JsonFile;

use Illuminate\Support\Arr;
use Symfony\Component\Filesystem\Path;
use Untek\Component\FileSystem\Helpers\FilePathHelper;
use Untek\Component\FileSystem\Helpers\FindFileHelper;

class DateRepository
{

    public function __construct(private string $directory)
    {
    }

    public function findLast()
    {
        $all = $this->findAll();
        return Arr::first($all);
    }

    public function findAll()
    {
        $dates = FindFileHelper::scanDir($this->directory);
        rsort($dates);
        $dates = array_slice($dates, 0, 30);
        foreach ($dates as $index => $date) {
            if (Path::getExtension($date) == 'log') {
                $dates[$index] = FilePathHelper::fileRemoveExt($date);
            } else {
                unset($dates[$index]);
            }
        }
        return $dates;
    }
}