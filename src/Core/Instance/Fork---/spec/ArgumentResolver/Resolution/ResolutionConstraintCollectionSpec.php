<?php

namespace spec\ArgumentResolver\Resolution;

use PhpSpec\ObjectBehavior;

class ResolutionConstraintCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType('Untek\Core\Instance\Fork\Resolution\ResolutionConstraintCollection');
    }
}
