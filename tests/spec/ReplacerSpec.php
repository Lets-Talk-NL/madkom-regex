<?php

namespace spec\Madkom\RegEx;

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
}
