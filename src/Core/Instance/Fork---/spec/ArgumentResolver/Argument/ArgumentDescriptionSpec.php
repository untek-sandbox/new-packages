<?php

namespace spec\ArgumentResolver\Argument;

use Untek\Core\Instance\Fork\Argument\ArgumentDescription;
use PhpSpec\ObjectBehavior;

class ArgumentDescriptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('foo', 0, ArgumentDescription::TYPE_SCALAR, true);
        $this->shouldHaveType('Untek\Core\Instance\Fork\Argument\ArgumentDescription');
    }
}
