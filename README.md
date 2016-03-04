PCRE RegEx wrapper around PHP regular expression functions
==========================================================

Whis package maintain various PCRE element implementations in Object Oriented way.
Including **Pattern**, **Matcher**, **Replacer** and **Splitter** objects.

---

## Installation

Install with Composer

```
composer require madkom/regex
```

## Usage

Using pattern:

```php
use Madkom\RegEx\Pattern;

$pattern = new Pattern('<((?=[^!])(/)?([^>]+))>');
$pattern->getPattern(); // "/<((?=[^!])(\/)?([^>]+))>/"
```

Matching pattern:

```php
use Madkom\RegEx\Matcher;
use Madkom\RegEx\Pattern;

$pattern = new Pattern('<((?=[^/]+)[^>]+)>');
$matcher = new Matcher($pattern, 's');

$subject = <<<EOL
<!DOCTYPE html>
<html>
<head><title>Ala ma kota</title></head>
<body><h1>Ala ma kota</h1></body>
</html>
EOL;

$match = $matcher->match($subject);

//array:2 [
//  0 => "<!DOCTYPE html>"
//  1 => "!DOCTYPE html"
//]
```

Matching pattern capture all matches:

```php
use Madkom\RegEx\Matcher;
use Madkom\RegEx\Pattern;

$pattern = new Pattern('<((?=[^/]+)[^>]+)>');
$matcher = new Matcher($pattern, 's');

$subject = <<<EOL
<!DOCTYPE html>
<html>
<head><title>Ala ma kota</title></head>
<body><h1>Ala ma kota</h1></body>
</html>
EOL;

$match = $matcher->matchAll($subject);

//array:2 [
//  0 => array:6 [
//    0 => "<!DOCTYPE html>"
//    1 => "<html>"
//    2 => "<head>"
//    3 => "<title>"
//    4 => "<body>"
//    5 => "<h1>"
//  ]
//  1 => array:6 [
//    0 => "!DOCTYPE html"
//    1 => "html"
//    2 => "head"
//    3 => "title"
//    4 => "body"
//    5 => "h1"
//  ]
//]
```

Replace with replacement:

```php
use Madkom\RegEx\Replacer;
use Madkom\RegEx\Pattern;

$pattern = new Pattern('<((?=[^!])(/)?([^>]+))>');
$replacer = new Replacer($pattern, 's');

$subject = <<<EOL
<!DOCTYPE html>
<html>
<head><title>Ala ma kota</title></head>
<body><h1>Ala ma kota</h1></body>
</html>
EOL;

$replaced = $replacer->replace($subject, '<\\2xhtml:\\3>');

//<!DOCTYPE html>\n
//<xhtml:html>\n
//<xhtml:head><xhtml:title>Ala ma kota</xhtml:title></xhtml:head>\n
//<xhtml:body><xhtml:h1>Ala ma kota</xhtml:h1></xhtml:body>\n
//</xhtml:html>
```

Replace with handler:

```php
use Madkom\RegEx\Replacer;
use Madkom\RegEx\Pattern;

$pattern = new Pattern('<((?=[^!])(/)?([^>]+))>');
$replacer = new Replacer($pattern, 's');

$subject = <<<EOL
<!DOCTYPE html>
<html>
<head><title>Ala ma kota</title></head>
<body><h1>Ala ma kota</h1></body>
</html>
EOL;

$replaced = $this->replaceWith($subject, function ($match) {
    return "<{$match[2]}xhtml:{$match[3]}>";
});

//<!DOCTYPE html>\n
//<xhtml:html>\n
//<xhtml:head><xhtml:title>Ala ma kota</xhtml:title></xhtml:head>\n
//<xhtml:body><xhtml:h1>Ala ma kota</xhtml:h1></xhtml:body>\n
//</xhtml:html>
```

## License

The MIT License (MIT)

Copyright (c) 2016 Madkom S.A.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.