<?php

namespace Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers;

use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Generator\TypeGenerator;
use Yiisoft\Strings\Inflector;

class ApplicationHelper
{

    public static function prepareProperties($command): array
    {
        $properties = [];
        foreach ($command->getProperties() as &$commandAttribute) {
            $name = (new Inflector())->toPascalCase($commandAttribute['name']);
            $type = $commandAttribute['type'];
            if (!empty($commandAttribute['nullable'])) {
                $type = '?' . $type;
            }

            $typeGenerator = TypeGenerator::fromTypeString($type);

            $propertyGenerator = new PropertyGenerator($name, '', PropertyGenerator::FLAG_PRIVATE, $typeGenerator);
            if (isset($commandAttribute['defaultValue'])) {
                $propertyGenerator->setDefaultValue($commandAttribute['defaultValue']);
            } else {
                $propertyGenerator->omitDefaultValue();
            }
            $properties[] = $propertyGenerator;
        }
        return $properties;
    }
}