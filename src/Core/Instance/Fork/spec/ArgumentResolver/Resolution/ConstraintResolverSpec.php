<?php

namespace spec\ArgumentResolver\Resolution;

use Untek\Core\Instance\Fork\Argument\ArgumentDescription;
use Untek\Core\Instance\Fork\Argument\ArgumentDescriptions;
use Untek\Core\Instance\Fork\Resolution\ResolutionConstraint;
use Untek\Core\Instance\Fork\Resolution\ResolutionConstraintCollection;
use PhpSpec\ObjectBehavior;

class ConstraintResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Untek\Core\Instance\Fork\Resolution\ConstraintResolver');
    }

    function it_returns_a_strict_matching_constraint_when_the_same_type_is_used_multiple_times_in_the_callable_arguments()
    {
        $descriptions = new ArgumentDescriptions([
            new ArgumentDescription('foo', 0, ArgumentDescription::TYPE_SCALAR, true),
            new ArgumentDescription('bar', 1, ArgumentDescription::TYPE_SCALAR, true),
            new ArgumentDescription('baz', 2, ArgumentDescription::TYPE_ARRAY, true),
            new ArgumentDescription('anObject', 3, 'A\\Class', true),
            new ArgumentDescription('anotherObject', 4, 'A\\Class', true),
        ]);

        $this->resolveConstraints($descriptions)->shouldBeLike(new ResolutionConstraintCollection([
            new ResolutionConstraint(ResolutionConstraint::STRICT_MATCHING, ['type' => ArgumentDescription::TYPE_SCALAR]),
            new ResolutionConstraint(ResolutionConstraint::STRICT_MATCHING, ['type' => 'A\\Class']),
        ]));
    }
}
