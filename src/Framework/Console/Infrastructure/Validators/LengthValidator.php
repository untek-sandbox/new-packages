<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class LengthValidator implements CliValidatorInterface
{

    public static function validate($length)
    {
        if (!$length) {
            return $length;
        }

        $result = filter_var($length, \FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if (false === $result) {
            throw new RuntimeCommandException(sprintf('Invalid length "%s".', $length));
        }

        return $result;
    }
}
