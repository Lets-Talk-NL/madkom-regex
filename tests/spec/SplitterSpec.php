<?php

namespace spec\Madkom\RegEx;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SplitterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Madkom\RegEx\Splitter');
    }
}
