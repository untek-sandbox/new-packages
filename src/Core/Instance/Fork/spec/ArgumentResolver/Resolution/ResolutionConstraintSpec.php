<?php

namespace spec\ArgumentResolver\Resolution;

use PhpSpec\ObjectBehavior;

class ResolutionConstraintSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(1);
        $this->shouldHaveType('Untek\Core\Instance\Fork\Resolution\ResolutionConstraint');
    }
}
