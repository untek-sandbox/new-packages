<?php

namespace spec\ArgumentResolver\Argument;

use PhpSpec\ObjectBehavior;

class ArgumentDescriptorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Untek\Core\Instance\Fork\Argument\ArgumentDescriptor');
    }
}
