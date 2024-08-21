<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class EmailValidator implements CliValidatorInterface
{

    public static function validate($email)
    {
        if (!filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeCommandException(sprintf('"%s" is not a valid email address.', $email));
        }

        return $email;
    }
}
