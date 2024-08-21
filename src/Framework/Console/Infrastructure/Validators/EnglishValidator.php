<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class EnglishValidator implements CliValidatorInterface
{

    public static function validate($value)
    {
        if (strlen($value) != strlen(utf8_decode($value))) {
            throw new RuntimeCommandException('This value is not english.');
        }
        return $value;
    }
}
