<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Core\Code\Helpers\PhpHelper;
use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;

class PropertyNameValidator implements CliValidatorInterface
{

    public static function validate($name)
    {
        EnglishValidator::validate($name);
        // check for valid PHP variable name
        if (!PhpHelper::isValidPhpVariableName($name)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid PHP property name.', $name));
        }

        return $name;
    }
}
