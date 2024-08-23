<?php

namespace Untek\Component\FormatAdapter;

use Symfony\Component\Filesystem\Path;
use Untek\Component\FileSystem\Helpers\FilePathHelper;

class StoreFile
{

    private $store;
    private $file;

    public function __construct($file, $driver = null)
    {
        $driver = $driver ?: Path::getExtension($file);
        $this->store = new Store($driver);
        $this->file = $file;
    }

    /*public function update($key, $value)
    {
        $this->store->update($this->file, $key, $value);
    }*/

    public function load($key = null)
    {
        return $this->store->load($this->file, $key);
    }

    public function save($data)
    {
        $this->store->save($this->file, $data);
    }

}