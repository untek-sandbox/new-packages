<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class ClassNotExistsValidator implements CliValidatorInterface
{

    public static function validate($className)
    {
        NotBlankValidator::validate($className);
        EnglishValidator::validate($className);

        if (class_exists($className)) {
            throw new RuntimeCommandException(sprintf('Class "%s" already exists.', $className));
        }

        return $className;
    }
}
