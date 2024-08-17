<?php

namespace spec\ArgumentResolver;

use Untek\Core\Instance\Fork\ArgumentResolver;
use Untek\Core\Instance\Fork\Resolution\ResolutionConstraint;
use PhpSpec\ObjectBehavior;

class CallableRunnerSpec extends ObjectBehavior
{
    function it_can_run_an_anonymous_function(ArgumentResolver $argumentResolver)
    {
        $callable = function ($foo) {
            return $foo;
        };
        $argumentResolver->resolveArguments($callable, [])->willReturn(['bar']);
        $this->beConstructedWith($argumentResolver);

        $this->run($callable, [])->shouldReturn('bar');
    }

    function it_can_run_a_class_method(ArgumentResolver $argumentResolver)
    {
        $resolutionConstraint = new ResolutionConstraint('type');
        $callable = [$resolutionConstraint, 'getType'];
        $argumentResolver->resolveArguments($callable, [])->willReturn([]);
        $this->beConstructedWith($argumentResolver);

        $this->run($callable, [])->shouldReturn('type');
    }
}
