<?php
/**
 * Created by PhpStorm.
 * User: mbrzuchalski
 * Date: 04.03.16
 * Time: 11:20
 */
namespace Madkom\RegEx;

/**
 * Class Pattern
 * @package Madkom\RegEx
 * @author Michał Brzuchalski <m.brzuchalski@madkom.pl>
 */
class Pattern
{
    const DELIMITER = '/';
    /**
     * @var string Holds compiled string pattern
     */
    private $pattern;

    /**
     * Pattern constructor.
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $delim = preg_quote(self::DELIMITER, self::DELIMITER);
        $this->pattern = self::DELIMITER . preg_replace("/([^\\\\])?({$delim})/", "\\1{$delim}", $pattern) . self::DELIMITER;
    }

    /**
     * Retrieves compiled pattern
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}
