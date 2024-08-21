<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class BooleanValidator implements CliValidatorInterface
{

    public static function validate($value)
    {
        if ('yes' == $value) {
            return true;
        }

        if ('no' == $value) {
            return false;
        }

        if (null === $valueAsBool = filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)) {
            throw new RuntimeCommandException(sprintf('Invalid bool value "%s".', $value));
        }

        return $valueAsBool;
    }
}
