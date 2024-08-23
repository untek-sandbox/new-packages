<?php

namespace Untek\Utility\CodeGeneratorApplication\Application\Helpers;

use Yiisoft\Strings\Inflector;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

class TypeHelper
{

    public static function generateCommandName(string $type, string $commandName)
    {
        $commandName = (new Inflector())->toPascalCase($commandName);
        if ($type === TypeEnum::COMMAND) {
            $commandClass = 'Commands\\' . $commandName . 'Command';
        } elseif ($type === TypeEnum::QUERY) {
            $commandClass = 'Queries\\' . $commandName . 'Query';
        }
        return $commandClass;
    }
}