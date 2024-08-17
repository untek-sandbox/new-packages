<?php

namespace Untek\Component\FormatAdapter\Drivers;

use Untek\Core\FileSystem\Helpers\FileStorageHelper;

class Html implements DriverInterface
{

    public function decode($code)
    {
        return $code;
    }

    public function encode($code)
    {
        return $code;
    }

    public function save($fileName, $data)
    {
        $content = $this->encode($data);
        FileStorageHelper::save($fileName, $content);
    }

    public function load($fileName, $key = null)
    {
        if (!FileStorageHelper::has($fileName)) {
            return null;
        }
        $data = FileStorageHelper::load($fileName);
        return $data;
    }
}