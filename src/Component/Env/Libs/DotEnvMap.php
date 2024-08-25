<?php

namespace Untek\Component\Env\Libs;

use Illuminate\Support\Arr;
use Untek\Component\Arr\Helpers\ArrayPathHelper;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\Pattern\Singleton\SingletonTrait;
use Yiisoft\Arrays\ArrayHelper;

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
        return ArrayPathHelper::getValue($this->map, $path, $default);
    }

    private function forgeMap(): void
    {
        foreach ($_ENV as $name => $value) {
            $pureName = $this->prepareName($name);
            Arr::set($this->map, $pureName, $value);
        }
    }

    private function prepareName(string $name): string
    {
        $name = strtolower($name);
        $name = str_replace('_', '.', $name);
        return $name;
    }
}
