<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Matcher;
use Madkom\RegEx\Pattern;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class MatcherSpec
 * @package spec\Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 * @mixin Matcher
 */
class MatcherSpec extends ObjectBehavior
{
    function let(Pattern $pattern)
    {
        $this->beConstructedWith($pattern);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Matcher::class);
    }

    function it_schould_match_subject(Pattern $pattern)
    {
        $subject = "<span>Ala <strong>ma kota</strong></span>";
        $pattern->getPattern()->willReturn('/<strong>([^<]+)<\/strong>/');
        $match = $this->match($subject);
        $match->shouldBeArray();
        $match->shouldHaveCount(2);
    }

    function it_schould_matchAll_subject(Pattern $pattern)
    {
        $subject = "<span>Ala <strong>ma kota</strong></span>";
        $pattern->getPattern()->willReturn('/<((?=[^\/]+)[^>]+)>/');
        $match = $this->matchAll($subject);
        $match->shouldBeArray();
        $match->shouldHaveCount(2);
    }
}
