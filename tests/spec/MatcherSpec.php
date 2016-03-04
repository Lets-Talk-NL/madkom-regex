<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Exception\BacktrackLimitException;
use Madkom\RegEx\Exception\BadUtf8Exception;
use Madkom\RegEx\Exception\JitStackLimitException;
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

    function it_can_handle_backtrack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/(?:\D+|<\d+>)*[!?]/');
        $this->beConstructedWith($pattern);
        $this->shouldThrow(BacktrackLimitException::class)->during('match', ['foobar foobar foobar']);
        $this->shouldThrow(BacktrackLimitException::class)->during('matchAll', ['foobar foobar foobar']);
    }

    function it_can_handle_jit_stack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/\\[(.|\\n)+\\]/');
        ini_set("pcre.recursion_limit", "16777");
        $this->shouldThrow(JitStackLimitException::class)->during('match', ['[' . str_repeat('A', 1025) . ']']);
        $this->shouldThrow(JitStackLimitException::class)->during('matchAll', ['[' . str_repeat('A', 1025) . ']']);
    }

    function it_can_handle_bad_utf8_error(Pattern $pattern)
    {
        $subject = "Cortège\x99 de gymnastique devant LL. MM. ęźżąśł";
        $pattern->getPattern()->willReturn('/\\x99/');
        $this->beConstructedWith($pattern, 'u');
        $this->shouldThrow(BadUtf8Exception::class)->during('match', [$subject]);
        $this->shouldThrow(BadUtf8Exception::class)->during('matchAll', [$subject]);
    }
}
