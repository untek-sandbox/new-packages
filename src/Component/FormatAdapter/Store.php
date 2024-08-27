<?php

namespace Untek\Component\FormatAdapter;

use Illuminate\Support\Arr;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Component\FormatAdapter\Drivers\DriverInterface;

class Store
{

    protected string $driver;

    protected DriverInterface $driverInstance;

    public function setDriver($driver)
    {
        $driver = strtolower($driver);
        $driver = ucfirst($driver);
        $this->driver = $driver;
        $driverClass = 'Untek\\Component\\FormatAdapter\\Drivers\\' . $driver;

        $this->driverInstance = new $driverClass();
    }

    public function getDriver()
    {
        return strtolower($this->driver);
    }

    public function __construct($driver)
    {
        $this->setDriver($driver);
    }

    public function decode($content, $key = null)
    {
        $data = $this->driverInstance->decode($content);
        if (empty($data)) {
            $data = [];
        }
        if (func_num_args() > 1) {
            return Arr::get($data, $key);
        }
        return $data;
    }

    public function encode($data)
    {
        return $this->driverInstance->encode($data);
    }

    /*public function update($fileAlias, $key, $value)
    {
        $data = $this->load($fileAlias);
        ExtArrayHelper::set($data, $key, $value);
        $this->save($fileAlias, $data);
    }*/

    public function load($fileName, $key = null)
    {
        if (!file_exists($fileName)) {
            return null;
        }
        if (method_exists($this->driverInstance, 'load')) {
            if (func_num_args() > 1) {
                return $this->driverInstance->load($fileName, $key);
            }
            return $this->driverInstance->load($fileName);
        }
        $content = file_get_contents($fileName);
        if (func_num_args() > 1) {
            return $this->decode($content, $key);
        }
        return $this->decode($content);
    }

    public function save($fileName, $data)
    {
        if (method_exists($this->driverInstance, 'save')) {
            return $this->driverInstance->save($fileName, $data);
        }
        $content = $this->encode($data);
        (new Filesystem())->dumpFile($fileName, $content);
    }

}