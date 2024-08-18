<?php

namespace Untek\Component\Env\Helpers;

use Untek\Component\Code\Helpers\DeprecateHelper;
use Untek\Component\Env\Enums\EnvEnum;

class EnvHelper
{

    public static function isDebug(): bool
    {
        return self::getAppDebug();
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
