<?php

namespace Untek\Core\Env\Helpers;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Env\Enums\EnvEnum;

class EnvHelper
{

    public static function isWeb(): bool
    {
        return !self::isConsole();
    }

    public static function isConsole(): bool
    {
        return in_array(PHP_SAPI, ['cli', 'phpdbg']);
    }

    public static function isDebug(): bool
    {
        return self::getAppDebug();
    }

    public static function isProd(): bool
    {
        return self::getAppEnv() == EnvEnum::PRODUCTION;
    }

    public static function isDev(): bool
    {
        return self::getAppEnv() == EnvEnum::DEVELOP;
    }

    public static function isTest(): bool
    {
        return self::getAppEnv() == EnvEnum::TEST;
    }

    private static function getAppEnv(): ?string
    {
        return getenv('APP_ENV') ?: EnvEnum::DEVELOP;
    }

    private static function getAppDebug(): ?string
    {
        return getenv('APP_DEBUG') ?: '0';
    }
}
