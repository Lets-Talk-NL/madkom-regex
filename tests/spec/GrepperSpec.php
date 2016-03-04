<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Exception\BacktrackLimitException;
use Madkom\RegEx\Exception\BadUtf8Exception;
use Madkom\RegEx\Exception\JitStackLimitException;
use Madkom\RegEx\Grepper;
use Madkom\RegEx\Pattern;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class GrepperSpec
 * @package spec\Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 * @mixin Grepper
 */
class GrepperSpec extends ObjectBehavior
{
    function let(Pattern $pattern)
    {
        $this->beConstructedWith($pattern);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Grepper::class);
    }

    function it_should_grep_subjects_array(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('(kot[^\s]+)');
        $this->beConstructedWith($pattern, 'i');
        $subjects = [
            'Ala ma kota',
            'A kot ma alę',
            'Kotka Mia',
            'Miała kocięta',
        ];
        $grepped = $this->grep($subjects);
        $grepped->shouldBeArray();
        $grepped->shouldHaveCount(2);
    }

    function it_can_handle_backtrack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/(?:\D+|<\d+>)*[!?]/');
        $this->beConstructedWith($pattern);
        $this->shouldThrow(BacktrackLimitException::class)->during('grep', [['foobar foobar foobar', 'foobar foobar foobar']]);
    }

    function it_can_handle_jit_stack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/\\[(.|\\n)+\\]/');
        ini_set("pcre.recursion_limit", "16777");
        $this->shouldThrow(JitStackLimitException::class)->during('grep', [['[' . str_repeat('A', 1025) . ']']]);
    }

    function it_can_handle_bad_utf8_error(Pattern $pattern)
    {
        $subject = "Cortège\x99 de gymnastique devant LL. MM. ęźżąśł";
        $pattern->getPattern()->willReturn('/\\x99/');
        $this->beConstructedWith($pattern, 'u');
        $this->shouldThrow(BadUtf8Exception::class)->during('grep', [[$subject]]);
    }
}
