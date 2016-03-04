<?php

namespace spec\Madkom\RegEx;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GrepperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Madkom\RegEx\Grepper');
    }
}
