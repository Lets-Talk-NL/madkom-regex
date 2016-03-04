<?php

namespace spec\Madkom\RegEx;

use Madkom\RegEx\Exception\BacktrackLimitException;
use Madkom\RegEx\Exception\BadUtf8Exception;
use Madkom\RegEx\Exception\JitStackLimitException;
use Madkom\RegEx\Pattern;
use Madkom\RegEx\Replacer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ReplacerSpec
 * @package spec\Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 * @mixin Replacer
 */
class ReplacerSpec extends ObjectBehavior
{
    function let(Pattern $pattern)
    {
        $this->beConstructedWith($pattern);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Replacer::class);
    }
    
    function it_should_replace_subject_with_replacement(Pattern $pattern)
    {
        $subject = <<<EOL
<!DOCTYPE html>
<html>
<head><title>Ala ma kota</title></head>
<body><h1>Ala ma kota</h1></body>
</html>
EOL;
        $pattern->getPattern()->willReturn('/<((?=[^!])(\/)?([^>]+))>/');
        $replaced = $this->replace($subject, '<\\2xhtml:\\3>');
        $replaced->shouldBeString();
        $replaced->shouldBeLike("<!DOCTYPE html>
<xhtml:html>
<xhtml:head><xhtml:title>Ala ma kota</xhtml:title></xhtml:head>
<xhtml:body><xhtml:h1>Ala ma kota</xhtml:h1></xhtml:body>
</xhtml:html>");

        $replaced = $this->replaceWith($subject, function ($match) {
            return "<{$match[2]}xhtml:{$match[3]}>";
        });
        $replaced->shouldBeString();
        $replaced->shouldBeLike("<!DOCTYPE html>
<xhtml:html>
<xhtml:head><xhtml:title>Ala ma kota</xhtml:title></xhtml:head>
<xhtml:body><xhtml:h1>Ala ma kota</xhtml:h1></xhtml:body>
</xhtml:html>");
    }

    function it_can_handle_backtrack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/(?:\D+|<\d+>)*[!?]/');
        $this->beConstructedWith($pattern);
        $this->shouldThrow(BacktrackLimitException::class)->during('replace', ['foobar foobar foobar', '|']);
        $this->shouldThrow(BacktrackLimitException::class)->during('replaceWith', ['foobar foobar foobar', function () {}]);
    }

    function it_can_handle_jit_stack_limit_error(Pattern $pattern)
    {
        $pattern->getPattern()->willReturn('/\\[(.|\\n)+\\]/');
        ini_set("pcre.recursion_limit", "16777");
        $this->shouldThrow(JitStackLimitException::class)->during('replace', ['[' . str_repeat('A', 1025) . ']', 'a']);
        $this->shouldThrow(JitStackLimitException::class)->during('replaceWith', ['[' . str_repeat('A', 1025) . ']', function () {}]);
    }

    function it_can_handle_bad_utf8_error(Pattern $pattern)
    {
        $subject = "Cortège\x99 de gymnastique devant LL. MM. ęźżąśł";
        $pattern->getPattern()->willReturn('/(\\x99)/');
        $this->beConstructedWith($pattern, 'u');
        $this->shouldThrow(BadUtf8Exception::class)->during('replace', [$subject, 'q']);
        $this->shouldThrow(BadUtf8Exception::class)->during('replaceWith', [$subject, function () {}]);
    }
}
