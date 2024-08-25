<?php

namespace Untek\Database\Base\Domain\Facades;

use Illuminate\Support\Arr;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\Env\Libs\DotEnvMap;
use Untek\Database\Base\Domain\Helpers\ConfigHelper;

class DbFacade
{

    public static function getConfigFromEnv(): array
    {
        if (getenv('DATABASE_URL')) {
            $connections['default'] = ConfigHelper::parseDsn(getenv('DATABASE_URL'));
        } else {
            $config = DotEnvMap::get('db');
            $isFlatConfig = !is_array(Arr::first($config));
            if ($isFlatConfig) {
                $connections['default'] = $config;
            } else {
                $connections = $config;
            }
        }
        foreach ($connections as &$connection) {
            $connection = ConfigHelper::prepareConfig($connection);
        }
        return $connections;
    }

}