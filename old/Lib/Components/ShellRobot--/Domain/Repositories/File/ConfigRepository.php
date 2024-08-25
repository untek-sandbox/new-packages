<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Repositories\File;

use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\ConfigRepositoryInterface;
use Yiisoft\Arrays\ArrayHelper;

class ConfigRepository implements ConfigRepositoryInterface
{

    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $key, $default = null)
    {
        $value = ExtArrayHelper::getValue($this->config, $key, $default);
        return $value;
    }
}
