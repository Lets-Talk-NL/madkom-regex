PCRE RegEx wrapper around PHP regular expression functions
==========================================================

Whis package maintain various PCRE element implementations in Object Oriented way.
Including **Pattern**, **Matcher**, **Replacer** and **Splitter** objects.

All methods can throw exceptions: **BacktrackLimitException**, **BadUtf8Exception**, **BadUtf8OffsetException**,
**InternalException**, **JitStackLimitException** and **RecursionLimitException** so you don't have to check
with `preg_last_error()` and comparing defines;

![PHP 7.0](https://img.shields.io/badge/PHP-7.0-8C9CB6.svg?style=flat)
[![Build Status](https://travis-ci.org/madkom/regex.svg?branch=master)](https://travis-ci.org/madkom/regex)
[![Latest Stable Version](https://poser.pugx.org/madkom/regex/v/stable)](https://packagist.org/packages/madkom/regex)
[![Total Downloads](https://poser.pugx.org/madkom/regex/downloads)](https://packagist.org/packages/madkom/regex)
[![License](https://poser.pugx.org/madkom/regex/license)](https://packagist.org/packages/madkom/regex)
[![Coverage Status](https://coveralls.io/repos/github/madkom/regex/badge.svg?branch=master)](https://coveralls.io/github/madkom/regex?branch=master)
[![Code Climate](https://codeclimate.com/github/madkom/regex/badges/gpa.svg)](https://codeclimate.com/github/madkom/regex)
[![Issue Count](https://codeclimate.com/github/madkom/regex/badges/issue_count.svg)](https://codeclimate.com/github/madkom/regex)

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

$replaced = $replacer->replaceWith($subject, function ($match) {
    return "<{$match[2]}xhtml:{$match[3]}>";
});

//<!DOCTYPE html>\n
//<xhtml:html>\n
//<xhtml:head><xhtml:title>Ala ma kota</xhtml:title></xhtml:head>\n
//<xhtml:body><xhtml:h1>Ala ma kota</xhtml:h1></xhtml:body>\n
//</xhtml:html>
```

Splitting:

```php
use Madkom\RegEx\Splitter;
use Madkom\RegEx\Pattern;

$subject = "html, simple, kayword, complex keyword, keyword with ; semicolon";

$pattern = new Pattern('([,]\s*)');
$splitter = new Splitter($pattern);
$splitted = $splitter->split($subject);

//array:5 [
//  0 => "html"
//  1 => "simple"
//  2 => "kayword"
//  3 => "complex keyword"
//  4 => "keyword with ; semicolon"
//]
```

Grepping array:

```php
use Madkom\RegEx\Grepper;
use Madkom\RegEx\Pattern;

$subjects = [
    '<a href="http://madkom.pl">madkom.pl</a>',
    '<a href="http://google.pl">google.pl</a>',
    '<a href="http://bing.com">bing.com</a>',
    '<a href="https://example.pl">example.pl</a>',
    '<a href="https://example.org">example.org</a>',
];

$pattern = new Pattern('(http[s]?://[a-z0-9]+\.pl)');
$grepper = new Grepper($pattern, 'iU');
$grepped = $grepper->grep($subjects);

//array:3 [
//  0 => "<a href="http://madkom.pl">madkom.pl</a>"
//  1 => "<a href="http://google.pl">google.pl</a>"
//  3 => "<a href="https://example.pl">example.pl</a>"
//]
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