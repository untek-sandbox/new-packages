<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class ClassExistsValidator implements CliValidatorInterface
{

    public static function validate($className, string $errorMessage = '')
    {
        NotBlankValidator::validate($className);
        EnglishValidator::validate($className);

        if (!class_exists($className)) {
            $errorMessage = $errorMessage ?: sprintf('Class "%s" doesn\'t exist; please enter an existing full class name.', $className);
            throw new RuntimeCommandException($errorMessage);
        }

        return $className;
    }
}
