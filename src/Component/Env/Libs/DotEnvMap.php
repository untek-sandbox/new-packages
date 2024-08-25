<?php

namespace Untek\Component\Env\Libs;

use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\Pattern\Singleton\SingletonTrait;

class DotEnvMap
{

    use SingletonTrait;

    private $map = [];

    private function __construct()
    {
        $this->forgeMap();
    }

    public static function get(string $path = null, $default = null)
    {
        return self::getInstance()->getValue($path, $default);
    }

    private function getValue(string $path = null, $default = null)
    {
        return ExtArrayHelper::getValue($this->map, $path, $default);
    }

    private function forgeMap(): void
    {
        foreach ($_ENV as $name => $value) {
            $pureName = $this->prepareName($name);
            ExtArrayHelper::set($this->map, $pureName, $value);
        }
    }

    private function prepareName(string $name): string
    {
        $name = strtolower($name);
        $name = str_replace('_', '.', $name);
        return $name;
    }
}
