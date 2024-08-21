<?php

namespace Untek\Framework\Console\Infrastructure\Validators;

use Untek\Framework\Console\Infrastructure\Interfaces\CliValidatorInterface;
use Untek\Framework\Console\Infrastructure\Exceptions\RuntimeCommandException;

class ScaleValidator implements CliValidatorInterface
{

    public static function validate($scale)
    {
        if (!$scale) {
            return $scale;
        }

        $result = filter_var($scale, \FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 30],
        ]);

        if (false === $result) {
            throw new RuntimeCommandException(sprintf('Invalid scale "%s".', $scale));
        }

        return $result;
    }
}
