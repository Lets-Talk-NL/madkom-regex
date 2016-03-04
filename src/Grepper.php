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
 * Class Grepper
 * @package Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Grepper
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
     * Grepper constructor.
     * @param Pattern $pattern
     * @param string $modifier
     */
    public function __construct(Pattern $pattern, string $modifier = '')
    {
        $this->pattern = $pattern;
        $this->modifier = $modifier;
    }

    /**
     * Retrieve grepped subjects array
     * @param array $subjects
     * @param int $flags
     * @return array
     * @throws BacktrackLimitException
     * @throws BadUtf8Exception
     * @throws BadUtf8OffsetException
     * @throws InternalException
     * @throws JitStackLimitException
     * @throws RecursionLimitException
     */
    public function grep(array $subjects, int $flags = 0) : array
    {
        $result = preg_grep($this->pattern->getPattern() . $this->modifier, $subjects, $flags);

        if (($errno = preg_last_error()) !== PREG_NO_ERROR) {
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
