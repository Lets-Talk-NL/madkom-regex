<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 04.03.16
 * Time: 12:20
 */
namespace Madkom\RegEx;

use Madkom\RegEx\Exception\BacktrackLimitException;
use Madkom\RegEx\Exception\BadUtf8Exception;
use Madkom\RegEx\Exception\BadUtf8OffsetException;
use Madkom\RegEx\Exception\InternalException;
use Madkom\RegEx\Exception\JitStackLimitException;
use Madkom\RegEx\Exception\RecursionLimitException;

/**
 * Class Replacer
 * @package Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Replacer
{
    /**
     * @var Pattern Holds compiled pattern
     */
    private $pattern;
    /**
     * @var string Holds modifier
     */
    private $modifier;

    /**
     * Matcher constructor.
     * @param Pattern $pattern
     * @param string $modifier
     */
    public function __construct(Pattern $pattern, string $modifier = '')
    {
        $this->pattern = $pattern;
        $this->modifier = $modifier;
    }

    /**
     * Retrieve replaced subject with replacement
     * @param string $subject
     * @param string $replacement
     * @param int $limit
     * @param int|null $count
     * @return string
     * @throws BacktrackLimitException
     * @throws BadUtf8Exception
     * @throws BadUtf8OffsetException
     * @throws InternalException
     * @throws JitStackLimitException
     * @throws RecursionLimitException
     */
    public function replace(string $subject, string $replacement, int $limit = -1, int &$count = null) : string
    {
        $result = preg_replace($this->pattern->getPattern() . $this->modifier, $replacement, $subject, $limit, $count);

        if ($errno = preg_last_error() !== PREG_NO_ERROR) {
            $message = array_flip(get_defined_constants(true)['pcre'])[$errno];
            switch ($errno) {
                case PREG_INTERNAL_ERROR:
                    throw new InternalException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_BACKTRACK_LIMIT_ERROR:
                    throw new BacktrackLimitException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_RECURSION_LIMIT_ERROR:
                    throw new RecursionLimitException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_BAD_UTF8_ERROR:
                    throw new BadUtf8Exception("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_BAD_UTF8_OFFSET_ERROR:
                    throw new BadUtf8OffsetException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_JIT_STACKLIMIT_ERROR:
                    throw new JitStackLimitException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
            }
        }

        return $result;
    }

    /**
     * Retrieve replaced subject with handler as replacer
     * @param string $subject
     * @param callable $handler
     * @param int $limit
     * @param int|null $count
     * @return string
     * @throws BacktrackLimitException
     * @throws BadUtf8Exception
     * @throws BadUtf8OffsetException
     * @throws InternalException
     * @throws JitStackLimitException
     * @throws RecursionLimitException
     */
    public function replaceWith(string $subject, callable $handler, int $limit = -1, int &$count = null) : string
    {
        $result = preg_replace_callback($this->pattern->getPattern() . $this->modifier, $handler, $subject, $limit, $count);

        if ($errno = preg_last_error() !== PREG_NO_ERROR) {
            $message = array_flip(get_defined_constants(true)['pcre'])[$errno];
            switch ($errno) {
                case PREG_INTERNAL_ERROR:
                    throw new InternalException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_BACKTRACK_LIMIT_ERROR:
                    throw new BacktrackLimitException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_RECURSION_LIMIT_ERROR:
                    throw new RecursionLimitException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_BAD_UTF8_ERROR:
                    throw new BadUtf8Exception("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_BAD_UTF8_OFFSET_ERROR:
                    throw new BadUtf8OffsetException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
                case PREG_JIT_STACKLIMIT_ERROR:
                    throw new JitStackLimitException("{$message} using pattern: {$this->pattern->getPattern()}", $errno);
            }
        }

        return $result;
    }
}
