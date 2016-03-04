<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Exception\BacktrackLimitException;
use Madkom\RegEx\Exception\BadUtf8Exception;
use Madkom\RegEx\Exception\JitStackLimitException;
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

    function it_can_handle_backtrack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/(?:\D+|<\d+>)*[!?]/');
        $this->beConstructedWith($pattern);
        $this->shouldThrow(BacktrackLimitException::class)->during('split', ['foobar foobar foobar']);
    }

    function it_can_handle_jit_stack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/\\[(.|\\n)+\\]/');
        ini_set("pcre.recursion_limit", "16777");
        $this->shouldThrow(JitStackLimitException::class)->during('split', ['[' . str_repeat('A', 1025) . ']']);
    }

    function it_can_handle_bad_utf8_error(Pattern $pattern)
    {
        $subject = "Cortège\x99 de gymnastique devant LL. MM. ęźżąśł";
        $pattern->getPattern()->willReturn('/\\x99/');
        $this->beConstructedWith($pattern, 'u');
        $this->shouldThrow(BadUtf8Exception::class)->during('split', [$subject]);
    }
}
