<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Pattern;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class PatternSpec
 * @package spec\Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 * @mixin Pattern
 */
class PatternSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('.*');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Pattern::class);
    }

    function it_should_compile_pattern()
    {
        $this->beConstructedWith('^/(.*)');
        $this->getPattern()->shouldReturn('/^\/(.*)/');
    }
}
