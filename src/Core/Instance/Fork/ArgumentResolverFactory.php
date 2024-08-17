<?php

namespace ArgumentResolver;

use Untek\Core\Instance\Fork\Argument\ArgumentDescriptor;
use Untek\Core\Instance\Fork\Resolution\ConstraintResolver;

final class ArgumentResolverFactory
{
    /**
     * Create an instance of ArgumentResolver.
     *
     * @return ArgumentResolver
     */
    public static function create()
    {
        return new ArgumentResolver(new ArgumentDescriptor(), new ConstraintResolver());
    }
}
