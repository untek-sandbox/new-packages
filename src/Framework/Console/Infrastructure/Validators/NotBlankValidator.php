<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class NotBlankValidator implements CliValidatorInterface
{

    public static function validate($value)
    {
        if (null === $value || '' === $value) {
            throw new RuntimeCommandException('This value cannot be blank.');
        }

        return $value;
    }
}
