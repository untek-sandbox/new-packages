<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class PrecisionValidator implements CliValidatorInterface
{

    public static function validate($precision)
    {
        if (!$precision) {
            return $precision;
        }

        $result = filter_var($precision, \FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65],
        ]);

        if (false === $result) {
            throw new RuntimeCommandException(sprintf('Invalid precision "%s".', $precision));
        }

        return $result;
    }
}
