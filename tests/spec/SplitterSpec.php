<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Pattern;
use Madkom\RegEx\Splitter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SplitterSpec
 * @package spec\Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 * @mixin Splitter
 */
class SplitterSpec extends ObjectBehavior
{
    function let(Pattern $pattern)
    {
        $this->beConstructedWith($pattern);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Splitter::class);
    }

    function it_should_split_keywords_with_colon(Pattern $pattern)
    {
        $subject = "html, simple, kayword, complex keyword, keyword with ; semicolon";
        $pattern->getPattern()->willReturn('/([,]\s*)/');
        $splitted = $this->split($subject);
        $splitted->shouldBeArray();
        $splitted->shouldHaveCount(5);
    }
}
